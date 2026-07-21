import '../models/task_model.dart';
import '../models/meal_model.dart';
import '../models/workout_model.dart';
import '../models/weight_log.dart';
import '../models/water_intake.dart';

class LocalNlpResult {
  final List<TaskModel>? tasks;
  final List<MealModel>? meals;
  final List<WorkoutModel>? workouts;
  final double? weight;
  final int? waterMl;
  final String? journalText;

  LocalNlpResult({
    this.tasks,
    this.meals,
    this.workouts,
    this.weight,
    this.waterMl,
    this.journalText,
  });
}

class VoiceNlpService {
  /// Extracts structured tasks, workouts, meals, weights, water, or journal logs from natural phrase.
  /// Runs fully offline using a rule-based regex parsing framework.
  LocalNlpResult parseInput(String text, String date) {
    final lower = text.toLowerCase();

    // Check for weight logs
    // e.g. "i weigh 85kg", "weight 78.5 kg", "logged weight of 90"
    final weightReg = RegExp(r'(?:weigh|weight|w)(?:\s+of)?\s*(\d+(?:\.\d+)?)\s*(?:kg|lbs)?');
    double? extractedWeight;
    if (weightReg.hasMatch(lower)) {
      final match = weightReg.firstMatch(lower);
      if (match != null && match.group(1) != null) {
        extractedWeight = double.tryParse(match.group(1)!);
      }
    }

    // Check for water logs
    // e.g. "drink 500ml water", "drank 250ml", "water 300 ml"
    final waterReg = RegExp(r'(?:drink|drank|water)\s*(\d+)\s*(?:ml|l)?');
    int? extractedWaterMl;
    if (waterReg.hasMatch(lower)) {
      final match = waterReg.firstMatch(lower);
      if (match != null && match.group(1) != null) {
        extractedWaterMl = int.tryParse(match.group(1)!);
      }
    }

    // Check for workouts
    // e.g. "logged gym workout for 45 minutes", "go to gym", "running for 30m"
    List<WorkoutModel>? extractedWorkouts;
    if (lower.contains('workout') || lower.contains('gym') || lower.contains('run') || lower.contains('walk') || lower.contains('exercise')) {
      final durationReg = RegExp(r'(\d+)\s*(?:minutes|mins|min|m)');
      int duration = 30; // default
      if (durationReg.hasMatch(lower)) {
        final m = durationReg.firstMatch(lower);
        if (m != null && m.group(1) != null) {
          duration = int.tryParse(m.group(1)!) ?? 30;
        }
      }
      String type = 'General';
      if (lower.contains('gym')) type = 'Gym';
      else if (lower.contains('run')) type = 'Running';
      else if (lower.contains('walk')) type = 'Walking';
      else if (lower.contains('home')) type = 'Home Workout';

      extractedWorkouts = [
        WorkoutModel(
          date: date,
          workoutType: type,
          durationMinutes: duration,
          caloriesBurned: duration * 8, // estimate
        )
      ];
    }

    // Check for meals
    // e.g. "ate chicken breast 500 calories with 40g protein" or "lunch chicken salad"
    List<MealModel>? extractedMeals;
    if (lower.contains('ate') || lower.contains('eat') || lower.contains('meal') || lower.contains('breakfast') || lower.contains('lunch') || lower.contains('dinner') || lower.contains('food')) {
      final calReg = RegExp(r'(\d+)\s*(?:calories|cal|cals)');
      final proteinReg = RegExp(r'(\d+)\s*(?:g protein|g of protein|g p|g)');

      int calories = 350; // estimate
      int protein = 20; // estimate

      if (calReg.hasMatch(lower)) {
        final m = calReg.firstMatch(lower);
        if (m != null && m.group(1) != null) {
          calories = int.tryParse(m.group(1)!) ?? 350;
        }
      }
      if (proteinReg.hasMatch(lower)) {
        final m = proteinReg.firstMatch(lower);
        if (m != null && m.group(1) != null) {
          protein = int.tryParse(m.group(1)!) ?? 20;
        }
      }

      String type = 'Snack';
      if (lower.contains('breakfast')) type = 'Breakfast';
      else if (lower.contains('lunch')) type = 'Lunch';
      else if (lower.contains('dinner')) type = 'Dinner';

      String foodName = 'Extracted Meal';
      if (lower.contains('chicken')) foodName = 'Chicken Dish';
      else if (lower.contains('salad')) foodName = 'Fresh Salad';
      else if (lower.contains('egg')) foodName = 'Boiled Eggs';

      extractedMeals = [
        MealModel(
          date: date,
          mealType: type,
          foodName: foodName,
          calories: calories,
          protein: protein,
        )
      ];
    }

    // Default to custom Task generation if it looks like a task list
    // e.g. "I need to finish my Flutter project, work for six hours, buy groceries..."
    List<TaskModel>? extractedTasks;
    if (extractedMeals == null && extractedWorkouts == null && extractedWeight == null && extractedWaterMl == null) {
      // Split by commas or "and" to separate multiple tasks
      final parts = text.split(RegExp(r',|and\s+'));
      extractedTasks = [];
      for (var part in parts) {
        final cleanPart = part.trim();
        if (cleanPart.length > 3) {
          // Estimate duration
          int duration = 30;
          if (cleanPart.contains('one hour') || cleanPart.contains('1 hour') || cleanPart.contains('1h')) duration = 60;
          else if (cleanPart.contains('two hours') || cleanPart.contains('2 hours') || cleanPart.contains('2h')) duration = 120;
          else if (cleanPart.contains('six hours') || cleanPart.contains('6 hours') || cleanPart.contains('6h')) duration = 360;

          // Estimate priority
          String priority = 'Medium';
          if (cleanPart.contains('must') || cleanPart.contains('critical') || cleanPart.contains('urgent') || cleanPart.contains('important')) {
            priority = 'High';
          }

          extractedTasks.add(TaskModel(
            title: cleanPart[0].toUpperCase() + cleanPart.substring(1),
            durationMinutes: duration,
            priority: priority,
            date: date,
            category: 'Morning briefing custom',
          ));
        }
      }
    }

    // Journal Entry text
    String? journalText;
    if (lower.contains('journal') || lower.contains('diary') || lower.contains('reflect')) {
      journalText = text;
    }

    return LocalNlpResult(
      tasks: extractedTasks,
      meals: extractedMeals,
      workouts: extractedWorkouts,
      weight: extractedWeight,
      waterMl: extractedWaterMl,
      journalText: journalText,
    );
  }
}
