import '../database/database_helper.dart';
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

class HunterRepository {
  final dbHelper = DatabaseHelper.instance;

  // USER PROFILE
  Future<UserProfile?> getUserProfile() async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('user_profile', limit: 1);
    if (maps.isEmpty) return null;
    return UserProfile.fromMap(maps.first);
  }

  Future<int> updateUserProfile(UserProfile profile) async {
    final db = await dbHelper.database;
    return await db.update(
      'user_profile',
      profile.toMap(),
      where: 'id = ?',
      whereArgs: [profile.id],
    );
  }

  // SETTINGS
  Future<AppSettings> getSettings() async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('settings', limit: 1);
    if (maps.isEmpty) return AppSettings();
    return AppSettings.fromMap(maps.first);
  }

  Future<int> updateSettings(AppSettings settings) async {
    final db = await dbHelper.database;
    return await db.update(
      'settings',
      settings.toMap(),
      where: 'id = ?',
      whereArgs: [settings.id],
    );
  }

  // TASKS
  Future<List<TaskModel>> getTasksForDate(String date) async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query(
      'tasks',
      where: 'date = ?',
      whereArgs: [date],
    );
    return maps.map((m) => TaskModel.fromMap(m)).toList();
  }

  Future<int> insertTask(TaskModel task) async {
    final db = await dbHelper.database;
    return await db.insert('tasks', task.toMap());
  }

  Future<int> updateTask(TaskModel task) async {
    final db = await dbHelper.database;
    return await db.update(
      'tasks',
      task.toMap(),
      where: 'id = ?',
      whereArgs: [task.id],
    );
  }

  Future<int> deleteTask(int id) async {
    final db = await dbHelper.database;
    return await db.delete(
      'tasks',
      where: 'id = ?',
      whereArgs: [id],
    );
  }

  // JOURNAL ENTRIES
  Future<JournalEntry?> getJournalEntryForDate(String date) async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query(
      'journal_entries',
      where: 'date = ?',
      whereArgs: [date],
    );
    if (maps.isEmpty) return null;
    return JournalEntry.fromMap(maps.first);
  }

  Future<int> insertOrUpdateJournal(JournalEntry entry) async {
    final db = await dbHelper.database;
    final existing = await getJournalEntryForDate(entry.date);
    if (existing != null) {
      return await db.update(
        'journal_entries',
        entry.toMap(),
        where: 'date = ?',
        whereArgs: [entry.date],
      );
    } else {
      return await db.insert('journal_entries', entry.toMap());
    }
  }

  // WEIGHT LOGS
  Future<List<WeightLog>> getWeightHistory() async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('weight_logs', orderBy: 'date ASC');
    return maps.map((m) => WeightLog.fromMap(m)).toList();
  }

  Future<int> logWeight(String date, double weight) async {
    final db = await dbHelper.database;
    final maps = await db.query('weight_logs', where: 'date = ?', whereArgs: [date]);
    if (maps.isNotEmpty) {
      return await db.update(
        'weight_logs',
        {'weight': weight},
        where: 'date = ?',
        whereArgs: [date],
      );
    } else {
      return await db.insert('weight_logs', {'date': date, 'weight': weight});
    }
  }

  // MEALS
  Future<List<MealModel>> getMealsForDate(String date) async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('meals', where: 'date = ?', whereArgs: [date]);
    return maps.map((m) => MealModel.fromMap(m)).toList();
  }

  Future<int> insertMeal(MealModel meal) async {
    final db = await dbHelper.database;
    return await db.insert('meals', meal.toMap());
  }

  Future<int> deleteMeal(int id) async {
    final db = await dbHelper.database;
    return await db.delete('meals', where: 'id = ?', whereArgs: [id]);
  }

  // WORKOUTS
  Future<List<WorkoutModel>> getWorkoutsForDate(String date) async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('workouts', where: 'date = ?', whereArgs: [date]);
    return maps.map((m) => WorkoutModel.fromMap(m)).toList();
  }

  Future<List<WorkoutModel>> getAllWorkouts() async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('workouts', orderBy: 'date DESC');
    return maps.map((m) => WorkoutModel.fromMap(m)).toList();
  }

  Future<int> insertWorkout(WorkoutModel workout) async {
    final db = await dbHelper.database;
    return await db.insert('workouts', workout.toMap());
  }

  Future<int> deleteWorkout(int id) async {
    final db = await dbHelper.database;
    return await db.delete('workouts', where: 'id = ?', whereArgs: [id]);
  }

  // WATER INTAKE
  Future<List<WaterIntake>> getWaterLogsForDate(String date) async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('water_intake', where: 'date = ?', whereArgs: [date]);
    return maps.map((m) => WaterIntake.fromMap(m)).toList();
  }

  Future<int> insertWater(WaterIntake water) async {
    final db = await dbHelper.database;
    return await db.insert('water_intake', water.toMap());
  }

  // SLEEP LOGS
  Future<List<SleepLog>> getSleepHistory() async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('sleep_logs', orderBy: 'date DESC');
    return maps.map((m) => SleepLog.fromMap(m)).toList();
  }

  Future<SleepLog?> getSleepForDate(String date) async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('sleep_logs', where: 'date = ?', whereArgs: [date]);
    if (maps.isEmpty) return null;
    return SleepLog.fromMap(maps.first);
  }

  Future<int> insertOrUpdateSleep(SleepLog sleep) async {
    final db = await dbHelper.database;
    final existing = await getSleepForDate(sleep.date);
    if (existing != null) {
      return await db.update(
        'sleep_logs',
        sleep.toMap(),
        where: 'date = ?',
        whereArgs: [sleep.date],
      );
    } else {
      return await db.insert('sleep_logs', sleep.toMap());
    }
  }

  // ACHIEVEMENTS
  Future<List<Achievement>> getAchievements() async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('achievements');
    return maps.map((m) => Achievement.fromMap(m)).toList();
  }

  Future<int> unlockAchievement(String title, String date) async {
    final db = await dbHelper.database;
    return await db.update(
      'achievements',
      {'is_unlocked': 1, 'unlocked_date': date},
      where: 'title = ?',
      whereArgs: [title],
    );
  }

  // REMINDERS
  Future<List<ReminderModel>> getReminders() async {
    final db = await dbHelper.database;
    final List<Map<String, dynamic>> maps = await db.query('reminders');
    return maps.map((m) => ReminderModel.fromMap(m)).toList();
  }

  Future<int> updateReminder(ReminderModel reminder) async {
    final db = await dbHelper.database;
    return await db.update(
      'reminders',
      reminder.toMap(),
      where: 'id = ?',
      whereArgs: [reminder.id],
    );
  }

  Future<int> insertReminder(ReminderModel reminder) async {
    final db = await dbHelper.database;
    return await db.insert('reminders', reminder.toMap());
  }

  // DATABASE BACKUP EXPORT & IMPORT
  Future<Map<String, dynamic>> exportBackup() async {
    final db = await dbHelper.database;
    final tables = [
      'user_profile', 'settings', 'tasks', 'journal_entries', 'weight_logs',
      'meals', 'workouts', 'water_intake', 'sleep_logs', 'achievements', 'reminders'
    ];
    final Map<String, dynamic> backup = {};
    for (var table in tables) {
      backup[table] = await db.query(table);
    }
    return backup;
  }

  Future<void> importBackup(Map<String, dynamic> backup) async {
    final db = await dbHelper.database;
    await db.transaction((txn) async {
      for (var table in backup.keys) {
        await txn.delete(table);
        final List<dynamic> rows = backup[table];
        for (var row in rows) {
          if (row is Map<String, dynamic>) {
            await txn.insert(table, row);
          }
        }
      }
    });
  }
}
