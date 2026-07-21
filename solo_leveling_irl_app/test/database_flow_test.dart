import 'package:flutter_test/flutter_test.dart';
import 'package:sqflite_common_ffi/sqflite_ffi.dart';
import 'package:solo_leveling_irl_app/database/database_helper.dart';
import 'package:solo_leveling_irl_app/repositories/hunter_repository.dart';
import 'package:solo_leveling_irl_app/models/user_profile.dart';
import 'package:solo_leveling_irl_app/models/task_model.dart';

void main() {
  sqfliteFfiInit();
  databaseFactory = databaseFactoryFfi;

  group('Persistent Database flow integration tests', () {
    late HunterRepository repository;

    setUp(() async {
      repository = HunterRepository();
      // Reset user to ensure predictable starting condition
      final db = await DatabaseHelper.instance.database;
      await db.delete('user_profile');
      await db.insert('user_profile', {
        'id': 1,
        'name': 'Jin-Woo',
        'level': 1,
        'xp': 0,
        'rank': 'E Rank',
        'streak': 0,
        'day_count': 1,
        'challenge_start_date': DateTime.now().toIso8601String().split('T')[0],
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
    });

    test('Loads pre-seeded user profile and updates properly', () async {
      final user = await repository.getUserProfile();
      expect(user, isNotNull);
      expect(user!.name, 'Jin-Woo');
      expect(user.level, 1);
      expect(user.rank, 'E Rank');

      // Update name & profile
      final updated = user.copyWith(name: 'Sung Jin-Woo', level: 5, rank: 'D Rank');
      await repository.updateUserProfile(updated);

      final reloaded = await repository.getUserProfile();
      expect(reloaded!.name, 'Sung Jin-Woo');
      expect(reloaded.level, 5);
      expect(reloaded.rank, 'D Rank');
    });

    test('CRUD operations for Tasks & Quests', () async {
      final date = '2026-07-21';
      final db = await DatabaseHelper.instance.database;
      await db.delete('tasks', where: 'date = ?', whereArgs: [date]);

      final tasksBefore = await repository.getTasksForDate(date);
      expect(tasksBefore.length, 0);

      final task = TaskModel(
        title: 'Clear Daily Dungeon C Rank',
        priority: 'High',
        category: 'Gym',
        date: date,
      );

      final id = await repository.insertTask(task);
      expect(id, greaterThan(0));

      final tasksAfter = await repository.getTasksForDate(date);
      expect(tasksAfter.length, 1);
      expect(tasksAfter.first.title, 'Clear Daily Dungeon C Rank');

      // Complete Task
      await repository.updateTask(tasksAfter.first.copyWith(isCompleted: true));
      final reloaded = await repository.getTasksForDate(date);
      expect(reloaded.first.isCompleted, isTrue);

      // Delete Task
      await repository.deleteTask(id);
      final tasksFinal = await repository.getTasksForDate(date);
      expect(tasksFinal.length, 0);
    });

    test('Data Export and Import back up integrity', () async {
      final backup = await repository.exportBackup();
      expect(backup, isNotEmpty);
      expect(backup.containsKey('user_profile'), isTrue);
      expect(backup.containsKey('settings'), isTrue);

      // Perform Import
      await repository.importBackup(backup);
      final user = await repository.getUserProfile();
      expect(user, isNotNull);
    });
  });
}
