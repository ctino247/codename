import 'package:sqflite/sqflite.dart';
import 'package:path/path.dart';

class DatabaseHelper {
  static final DatabaseHelper instance = DatabaseHelper._init();
  static Database? _database;

  DatabaseHelper._init();

  Future<Database> get database async {
    if (_database != null) return _database!;
    _database = await _initDB('solo_leveling_irl.db');
    return _database!;
  }

  Future<Database> _initDB(String filePath) async {
    final dbPath = await getDatabasesPath();
    final path = join(dbPath, filePath);

    return await openDatabase(
      path,
      version: 1,
      onCreate: _createDB,
    );
  }

  Future<void> _createDB(Database db, int version) async {
    // 1. User Profile Table
    await db.execute('''
      CREATE TABLE user_profile (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        level INTEGER NOT NULL DEFAULT 1,
        xp INTEGER NOT NULL DEFAULT 0,
        rank TEXT NOT NULL DEFAULT 'E Rank',
        streak INTEGER NOT NULL DEFAULT 0,
        day_count INTEGER NOT NULL DEFAULT 1,
        challenge_start_date TEXT NOT NULL,
        age INTEGER,
        height REAL,
        weight REAL,
        target_weight REAL,
        target_date TEXT,
        gender TEXT,
        activity_level TEXT,
        workout_preference TEXT,
        food_preferences TEXT,
        calorie_goal INTEGER,
        protein_goal INTEGER,
        water_goal INTEGER
      )
    ''');

    // 2. Settings Table
    await db.execute('''
      CREATE TABLE settings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        wake_time TEXT NOT NULL DEFAULT '06:00',
        sleep_time TEXT NOT NULL DEFAULT '22:00',
        reminder_sounds INTEGER NOT NULL DEFAULT 1,
        anime_voice INTEGER NOT NULL DEFAULT 1,
        notification_frequency TEXT NOT NULL DEFAULT 'Normal',
        weight_unit TEXT NOT NULL DEFAULT 'kg',
        distance_unit TEXT NOT NULL DEFAULT 'km',
        dark_mode INTEGER NOT NULL DEFAULT 1
      )
    ''');

    // 3. Tasks Table
    await db.execute('''
      CREATE TABLE tasks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT,
        duration_minutes INTEGER NOT NULL DEFAULT 30,
        priority TEXT NOT NULL DEFAULT 'Medium',
        scheduled_time TEXT,
        category TEXT NOT NULL DEFAULT 'Custom',
        is_completed INTEGER NOT NULL DEFAULT 0,
        date TEXT NOT NULL
      )
    ''');

    // 4. Daily Schedules Table
    await db.execute('''
      CREATE TABLE daily_schedules (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        date TEXT NOT NULL UNIQUE,
        morning_routine_done INTEGER NOT NULL DEFAULT 0,
        night_routine_done INTEGER NOT NULL DEFAULT 0,
        summary TEXT
      )
    ''');

    // 5. Journal Entries Table
    await db.execute('''
      CREATE TABLE journal_entries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        date TEXT NOT NULL,
        entry TEXT NOT NULL,
        mood TEXT,
        energy_level INTEGER,
        wins TEXT,
        problems TEXT
      )
    ''');

    // 6. Weight Logs Table
    await db.execute('''
      CREATE TABLE weight_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        date TEXT NOT NULL UNIQUE,
        weight REAL NOT NULL
      )
    ''');

    // 7. Meals Table
    await db.execute('''
      CREATE TABLE meals (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        date TEXT NOT NULL,
        meal_type TEXT NOT NULL, -- Breakfast, Lunch, Dinner, Snack
        food_name TEXT NOT NULL,
        calories INTEGER NOT NULL,
        protein INTEGER NOT NULL
      )
    ''');

    // 8. Workouts Table
    await db.execute('''
      CREATE TABLE workouts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        date TEXT NOT NULL,
        workout_type TEXT NOT NULL, -- Walking, Gym, Home, Running etc.
        duration_minutes INTEGER NOT NULL,
        calories_burned INTEGER NOT NULL,
        notes TEXT
      )
    ''');

    // 9. Water Intake Table
    await db.execute('''
      CREATE TABLE water_intake (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        date TEXT NOT NULL,
        amount_ml INTEGER NOT NULL,
        timestamp TEXT NOT NULL
      )
    ''');

    // 10. Sleep Logs Table
    await db.execute('''
      CREATE TABLE sleep_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        date TEXT NOT NULL UNIQUE,
        bedtime TEXT NOT NULL,
        wake_time TEXT NOT NULL,
        duration_hours REAL NOT NULL,
        quality_score INTEGER NOT NULL
      )
    ''');

    // 11. XP History Table
    await db.execute('''
      CREATE TABLE xp_history (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        date TEXT NOT NULL,
        amount INTEGER NOT NULL,
        source TEXT NOT NULL,
        timestamp TEXT NOT NULL
      )
    ''');

    // 12. Achievements Table
    await db.execute('''
      CREATE TABLE achievements (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        unlocked_date TEXT,
        is_unlocked INTEGER NOT NULL DEFAULT 0,
        badge_name TEXT NOT NULL
      )
    ''');

    // 13. Reminders Table
    await db.execute('''
      CREATE TABLE reminders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        time TEXT NOT NULL,
        is_active INTEGER NOT NULL DEFAULT 1,
        type TEXT NOT NULL -- water, workout, wake, sleep, custom
      )
    ''');

    // 14. Voice Notes Table
    await db.execute('''
      CREATE TABLE voice_notes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        date TEXT NOT NULL,
        transcription TEXT NOT NULL,
        timestamp TEXT NOT NULL
      )
    ''');

    // Populate Initial Data
    await _insertInitialData(db);
  }

  Future<void> _insertInitialData(Database db) async {
    final nowStr = DateTime.now().toIso8601String().split('T')[0];

    // Initial User Profile
    await db.insert('user_profile', {
      'name': 'Jin-Woo',
      'level': 1,
      'xp': 0,
      'rank': 'E Rank',
      'streak': 0,
      'day_count': 1,
      'challenge_start_date': nowStr,
      'age': 24,
      'height': 178.0,
      'weight': 80.0,
      'target_weight': 72.0,
      'target_date': DateTime.now().add(const Duration(days: 100)).toIso8601String().split('T')[0],
      'gender': 'Male',
      'activity_level': 'Moderate',
      'workout_preference': 'Gym Workouts',
      'food_preferences': 'High protein, meat, eggs, rice',
      'calorie_goal': 2200,
      'protein_goal': 150,
      'water_goal': 3000,
    });

    // Initial Settings
    await db.insert('settings', {
      'wake_time': '06:00',
      'sleep_time': '22:00',
      'reminder_sounds': 1,
      'anime_voice': 1,
      'notification_frequency': 'Normal',
      'weight_unit': 'kg',
      'distance_unit': 'km',
      'dark_mode': 1,
    });

    // Seed Achievements
    final List<Map<String, dynamic>> initialAchievements = [
      {'title': 'First Step of the Hunter', 'description': 'Complete the onboarding initiation.', 'badge_name': 'hunter_license', 'is_unlocked': 1, 'unlocked_date': nowStr},
      {'title': 'Stamina Recovered', 'description': 'Complete your target water intake for 3 consecutive days.', 'badge_name': 'elixir_flask', 'is_unlocked': 0},
      {'title': 'Iron Will', 'description': 'Complete all daily quests 7 days in a row.', 'badge_name': 'iron_shield', 'is_unlocked': 0},
      {'title': 'Shadow Lord Ascendant', 'description': 'Unlock the Monarch rank.', 'badge_name': 'monarch_crown', 'is_unlocked': 0},
      {'title': 'The Dungeon Sweeper', 'description': 'Complete 50 daily custom tasks.', 'badge_name': 'broken_gate', 'is_unlocked': 0},
    ];

    for (var ach in initialAchievements) {
      await db.insert('achievements', ach);
    }

    // Seed Default Reminders
    final List<Map<String, dynamic>> defaultReminders = [
      {'title': 'Wake up and conquer!', 'time': '06:00', 'is_active': 1, 'type': 'wake'},
      {'title': 'Drink water to recover stamina', 'time': '09:00', 'is_active': 1, 'type': 'water'},
      {'title': 'Time to fuel up (Breakfast)', 'time': '08:00', 'is_active': 1, 'type': 'breakfast'},
      {'title': 'Stamina replenishment (Lunch)', 'time': '13:00', 'is_active': 1, 'type': 'lunch'},
      {'title': 'Evening Feast (Dinner)', 'time': '19:00', 'is_active': 1, 'type': 'dinner'},
      {'title': 'Enter the Training Center (Workout)', 'time': '18:00', 'is_active': 1, 'type': 'workout'},
      {'title': 'Rest and recovery (Sleep)', 'time': '22:00', 'is_active': 1, 'type': 'sleep'},
    ];

    for (var reminder in defaultReminders) {
      await db.insert('reminders', reminder);
    }
  }

  Future<void> close() async {
    final db = _database;
    if (db != null) {
      await db.close();
    }
  }
}
