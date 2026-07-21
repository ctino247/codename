import 'package:flutter_test/flutter_test.dart';
import 'package:solo_leveling_irl_app/services/voice_nlp_service.dart';
import 'package:solo_leveling_irl_app/services/smart_planner_service.dart';
import 'package:solo_leveling_irl_app/services/xp_engine_service.dart';
import 'package:solo_leveling_irl_app/services/nutrition_engine_service.dart';
import 'package:solo_leveling_irl_app/models/user_profile.dart';
import 'package:solo_leveling_irl_app/models/task_model.dart';

void main() {
  group('Voice NLP Service Tests', () {
    final service = VoiceNlpService();

    test('Parses Weight Log Correctly', () {
      final res = service.parseInput('I weigh 85.5 kg today', '2026-07-21');
      expect(res.weight, 85.5);
    });

    test('Parses Water Log Correctly', () {
      final res = service.parseInput('Drank 500ml water to recover stamina', '2026-07-21');
      expect(res.waterMl, 500);
    });

    test('Parses Workouts Correctly', () {
      final res = service.parseInput('I just did a gym workout for 45 minutes', '2026-07-21');
      expect(res.workouts, isNotNull);
      expect(res.workouts!.first.workoutType, 'Gym');
      expect(res.workouts!.first.durationMinutes, 45);
    });

    test('Parses Meal Logs Correctly', () {
      final res = service.parseInput('I ate chicken salad, 600 calories and 40g of protein', '2026-07-21');
      expect(res.meals, isNotNull);
      expect(res.meals!.first.calories, 600);
      expect(res.meals!.first.protein, 40);
    });
  });

  group('Smart Planner Service Tests', () {
    final planner = SmartPlannerService();

    test('Generates Full Schedule from Custom Tasks', () {
      final tasks = [
        TaskModel(title: 'Work on Flutter project', priority: 'High', date: '2026-07-21', durationMinutes: 120),
        TaskModel(title: 'Study math', priority: 'Medium', date: '2026-07-21', durationMinutes: 60),
      ];

      final res = planner.generateSmartSchedule(
        date: '2026-07-21',
        wakeTime: '06:00',
        sleepTime: '22:00',
        customTasks: tasks,
      );

      expect(res.length, greaterThanOrEqualTo(5));
      expect(res.any((t) => t.title == 'Work on Flutter project'), isTrue);
    });
  });

  group('XP Engine Service Tests', () {
    final xpEngine = XpEngineService();

    test('Calculates XP level-up correctly', () {
      final profile = UserProfile(name: 'Jin-Woo', level: 1, xp: 80, challengeStartDate: '2026-07-21');
      final result = xpEngine.processXpAward(profile, 50);

      expect(result.didLevelUp, isTrue);
      expect(result.newLevel, 2);
      expect(result.newXp, 30); // 80 + 50 = 130. Level 1 needs 100 XP. So 130 - 100 = 30 XP remaining.
    });

    test('Determines ranks correctly', () {
      expect(xpEngine.determineRank(4), 'E Rank');
      expect(xpEngine.determineRank(5), 'D Rank');
      expect(xpEngine.determineRank(85), 'Shadow Monarch');
    });
  });

  group('Nutrition Engine Service Tests', () {
    final nutrition = NutritionEngineService();

    test('Calculates custom calorie guidelines correctly', () {
      final res = nutrition.calculateMacros(
        gender: 'Male',
        age: 25,
        height: 180,
        weight: 80,
        activityLevel: 'Moderate',
        goal: 'Lose Weight',
      );

      expect(res['calories'], isNotNull);
      expect(res['protein'], 160); // 80kg * 2
      expect(res['water'], 2800); // 80kg * 35
    });
  });
}
