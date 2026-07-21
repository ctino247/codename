import 'package:flutter/material.dart';
import '../models/user_profile.dart';
import '../models/app_settings.dart';
import '../models/task_model.dart';
import '../models/journal_entry.dart';
import '../models/weight_log.dart';
import '../models/meal_model.dart';
import '../models/workout_model.dart';
import '../models/water_intake.dart';
import '../models/sleep_log.dart';
import '../models/achievement.dart';
import '../models/reminder_model.dart';
import '../repositories/hunter_repository.dart';
import '../services/voice_nlp_service.dart';
import '../services/smart_planner_service.dart';
import '../services/xp_engine_service.dart';
import '../services/nutrition_engine_service.dart';

class SystemStateProvider extends ChangeNotifier {
  final HunterRepository repository = HunterRepository();
  final VoiceNlpService voiceNlpService = VoiceNlpService();
  final SmartPlannerService smartPlannerService = SmartPlannerService();
  final XpEngineService xpEngineService = XpEngineService();
  final NutritionEngineService nutritionEngineService = NutritionEngineService();

  UserProfile? userProfile;
  AppSettings? settings;
  List<TaskModel> todayTasks = [];
  List<MealModel> todayMeals = [];
  List<WorkoutModel> todayWorkouts = [];
  List<WaterIntake> todayWater = [];
  List<WeightLog> weightHistory = [];
  List<SleepLog> sleepHistory = [];
  List<Achievement> achievements = [];
  List<ReminderModel> reminders = [];
  JournalEntry? todayJournal;

  bool isLoading = true;

  SystemStateProvider() {
    init();
  }

  Future<void> init() async {
    isLoading = true;
    notifyListeners();

    userProfile = await repository.getUserProfile();
    settings = await repository.getSettings();
    final todayStr = DateTime.now().toIso8601String().split('T')[0];

    todayTasks = await repository.getTasksForDate(todayStr);
    todayMeals = await repository.getMealsForDate(todayStr);
    todayWorkouts = await repository.getWorkoutsForDate(todayStr);
    todayWater = await repository.getWaterLogsForDate(todayStr);
    weightHistory = await repository.getWeightHistory();
    sleepHistory = await repository.getSleepHistory();
    achievements = await repository.getAchievements();
    reminders = await repository.getReminders();
    todayJournal = await repository.getJournalEntryForDate(todayStr);

    isLoading = false;
    notifyListeners();
  }

  // Reload current date data
  Future<void> refreshTodayData() async {
    final todayStr = DateTime.now().toIso8601String().split('T')[0];
    todayTasks = await repository.getTasksForDate(todayStr);
    todayMeals = await repository.getMealsForDate(todayStr);
    todayWorkouts = await repository.getWorkoutsForDate(todayStr);
    todayWater = await repository.getWaterLogsForDate(todayStr);
    weightHistory = await repository.getWeightHistory();
    sleepHistory = await repository.getSleepHistory();
    achievements = await repository.getAchievements();
    todayJournal = await repository.getJournalEntryForDate(todayStr);
    notifyListeners();
  }

  // ADD XP
  Future<void> awardXp(int amount, String source) async {
    if (userProfile == null) return;
    final res = xpEngineService.processXpAward(userProfile!, amount);

    userProfile = userProfile!.copyWith(
      xp: res.newXp,
      level: res.newLevel,
      rank: res.newRank,
    );
    await repository.updateUserProfile(userProfile!);

    // Log XP History in Db if desired
    notifyListeners();
  }

  // TASK OPERATIONS
  Future<void> addTask(String title, int duration, String priority, String category) async {
    final todayStr = DateTime.now().toIso8601String().split('T')[0];
    final task = TaskModel(
      title: title,
      durationMinutes: duration,
      priority: priority,
      category: category,
      date: todayStr,
    );
    await repository.insertTask(task);
    await refreshTodayData();
    await awardXp(15, "Completed customization task additions.");
  }

  Future<void> toggleTask(TaskModel task) async {
    final updated = task.copyWith(isCompleted: !task.isCompleted);
    await repository.updateTask(updated);
    if (updated.isCompleted) {
      await awardXp(25, "Finished Task: ${task.title}");
    } else {
      await awardXp(-25, "Unchecked Task");
    }
    await refreshTodayData();
  }

  Future<void> removeTask(int id) async {
    await repository.deleteTask(id);
    await refreshTodayData();
  }

  // SMART DAILY SCHEDULING
  Future<void> triggerSmartPlanner(List<TaskModel> customTasks) async {
    if (userProfile == null || settings == null) return;
    final todayStr = DateTime.now().toIso8601String().split('T')[0];

    final smartTasks = smartPlannerService.generateSmartSchedule(
      date: todayStr,
      wakeTime: settings!.wakeTime,
      sleepTime: settings!.sleepTime,
      customTasks: customTasks,
    );

    // Delete existing custom morning briefing tasks to prevent duplicates, then insert newly generated ones
    for (var existingTask in todayTasks) {
      if (existingTask.category == 'Routine' || existingTask.category == 'Nutrition' || existingTask.category == 'Custom Task' || existingTask.category == 'Sleep Prep') {
        await repository.deleteTask(existingTask.id!);
      }
    }

    for (var task in smartTasks) {
      await repository.insertTask(task);
    }

    await refreshTodayData();
    await awardXp(30, "Intelligent Daily Dungeon Generation");
  }

  // WATER
  Future<void> logWaterIntake(int ml) async {
    final todayStr = DateTime.now().toIso8601String().split('T')[0];
    final log = WaterIntake(
      date: todayStr,
      amountMl: ml,
      timestamp: DateTime.now().toIso8601String(),
    );
    await repository.insertWater(log);
    await refreshTodayData();
    await awardXp(10, "Stamina Recovery (Water Intake)");
  }

  // WEIGHT
  Future<void> updateWeight(double weight) async {
    final todayStr = DateTime.now().toIso8601String().split('T')[0];
    await repository.logWeight(todayStr, weight);
    if (userProfile != null) {
      userProfile = userProfile!.copyWith(weight: weight);
      await repository.updateUserProfile(userProfile!);
    }
    await refreshTodayData();
    await awardXp(20, "Logged current weight: $weight kg");
  }

  // MEALS
  Future<void> addMeal(String type, String food, int calories, int protein) async {
    final todayStr = DateTime.now().toIso8601String().split('T')[0];
    final meal = MealModel(
      date: todayStr,
      mealType: type,
      foodName: food,
      calories: calories,
      protein: protein,
    );
    await repository.insertMeal(meal);
    await refreshTodayData();
    await awardXp(15, "Fuel Logged: $food");
  }

  Future<void> deleteMealLog(int id) async {
    await repository.deleteMeal(id);
    await refreshTodayData();
  }

  // WORKOUTS
  Future<void> logWorkout(String type, int duration, int calories, String notes) async {
    final todayStr = DateTime.now().toIso8601String().split('T')[0];
    final workout = WorkoutModel(
      date: todayStr,
      workoutType: type,
      durationMinutes: duration,
      caloriesBurned: calories,
      notes: notes,
    );
    await repository.insertWorkout(workout);
    await refreshTodayData();
    await awardXp(50, "Completed Daily Training Dungeon ($type)");
  }

  Future<void> deleteWorkoutLog(int id) async {
    await repository.deleteWorkout(id);
    await refreshTodayData();
  }

  // JOURNAL & NIGHT REVIEW
  Future<void> logJournal(String entry, String mood, int energy, String wins, String problems) async {
    final todayStr = DateTime.now().toIso8601String().split('T')[0];
    final journal = JournalEntry(
      date: todayStr,
      entry: entry,
      mood: mood,
      energyLevel: energy,
      wins: wins,
      problems: problems,
    );
    await repository.insertOrUpdateJournal(journal);
    await refreshTodayData();
    await awardXp(40, "Night Dungeon Review complete");
  }

  // ONBOARDING INITIATION SETUP
  Future<void> saveOnboardingSetup({
    required String name,
    required int age,
    required double height,
    required double weight,
    required double targetWeight,
    required String gender,
    required String activityLevel,
    required String workoutPreference,
    required String foodPreferences,
    required String goal,
  }) async {
    final macros = nutritionEngineService.calculateMacros(
      gender: gender,
      age: age,
      height: height,
      weight: weight,
      activityLevel: activityLevel,
      goal: goal,
    );

    final todayStr = DateTime.now().toIso8601String().split('T')[0];

    final updatedProfile = UserProfile(
      id: userProfile?.id ?? 1,
      name: name,
      level: userProfile?.level ?? 1,
      xp: userProfile?.xp ?? 0,
      rank: userProfile?.rank ?? 'E Rank',
      streak: userProfile?.streak ?? 0,
      dayCount: userProfile?.dayCount ?? 1,
      challengeStartDate: userProfile?.challengeStartDate ?? todayStr,
      age: age,
      height: height,
      weight: weight,
      targetWeight: targetWeight,
      targetDate: DateTime.now().add(const Duration(days: 100)).toIso8601String().split('T')[0],
      gender: gender,
      activityLevel: activityLevel,
      workoutPreference: workoutPreference,
      foodPreferences: foodPreferences,
      calorieGoal: macros['calories'],
      proteinGoal: macros['protein'],
      waterGoal: macros['water'],
    );

    await repository.updateUserProfile(updatedProfile);
    userProfile = updatedProfile;
    notifyListeners();
  }

  // SLEEP
  Future<void> logSleep(String bedtime, String wakeTime, double hours, int quality) async {
    final todayStr = DateTime.now().toIso8601String().split('T')[0];
    final sleep = SleepLog(
      date: todayStr,
      bedtime: bedtime,
      wakeTime: wakeTime,
      durationHours: hours,
      qualityScore: quality,
    );
    await repository.insertOrUpdateSleep(sleep);
    await refreshTodayData();
    await awardXp(40, "Overnight Recovery Sleep logged");
  }

  // NLP TEXT/SPEECH HANDLER
  Future<String> processNlpInput(String input) async {
    final todayStr = DateTime.now().toIso8601String().split('T')[0];
    final nlpResult = voiceNlpService.parseInput(input, todayStr);

    String feedback = "The System analyzed your words, Hunter:\n";

    if (nlpResult.weight != null) {
      await updateWeight(nlpResult.weight!);
      feedback += "• Logged weight to ${nlpResult.weight} kg\n";
    }
    if (nlpResult.waterMl != null) {
      await logWaterIntake(nlpResult.waterMl!);
      feedback += "• Stored stamina recovery water of ${nlpResult.waterMl} ml\n";
    }
    if (nlpResult.workouts != null && nlpResult.workouts!.isNotEmpty) {
      for (var w in nlpResult.workouts!) {
        await logWorkout(w.workoutType, w.durationMinutes, w.caloriesBurned, "Parsed offline voice notes");
        feedback += "• Logged training dungeon session (${w.workoutType}, ${w.durationMinutes}m)\n";
      }
    }
    if (nlpResult.meals != null && nlpResult.meals!.isNotEmpty) {
      for (var m in nlpResult.meals!) {
        await addMeal(m.mealType, m.foodName, m.calories, m.protein);
        feedback += "• Logged recovery meal (${m.foodName}, ${m.calories} cal)\n";
      }
    }
    if (nlpResult.tasks != null && nlpResult.tasks!.isNotEmpty) {
      await triggerSmartPlanner(nlpResult.tasks!);
      feedback += "• Dynamically arranged daily timeline around custom objectives.\n";
    }

    if (feedback == "The System analyzed your words, Hunter:\n") {
      feedback = "Speech recognized, but the System could not find exact actions. Standard objective parsed.";
      await addTask(input, 30, 'Medium', 'Voice Task');
    }

    return feedback;
  }

  // Backup Facilities
  Future<String> exportData() async {
    try {
      final data = await repository.exportBackup();
      return data.toString();
    } catch (e) {
      return "Error exporting: $e";
    }
  }
}
