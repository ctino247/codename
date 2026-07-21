import '../models/user_profile.dart';

class XpResult {
  final int newXp;
  final int newLevel;
  final String newRank;
  final bool didLevelUp;

  XpResult({
    required this.newXp,
    required this.newLevel,
    required this.newRank,
    required this.didLevelUp,
  });
}

class XpEngineService {
  static const int xpPerLevelBase = 100;

  /// Calculates XP threshold for a given level
  int getXpThreshold(int level) {
    return level * xpPerLevelBase;
  }

  /// Processes added XP, levels up if threshold crossed, and dynamically determines Hunter Rank.
  XpResult processXpAward(UserProfile profile, int xpGained) {
    int currentXp = profile.xp + xpGained;
    int currentLevel = profile.level;
    bool didLevelUp = false;

    while (currentXp >= getXpThreshold(currentLevel)) {
      currentXp -= getXpThreshold(currentLevel);
      currentLevel++;
      didLevelUp = true;
    }

    final String rank = determineRank(currentLevel);

    return XpResult(
      newXp: currentXp,
      newLevel: currentLevel,
      newRank: rank,
      didLevelUp: didLevelUp,
    );
  }

  /// Map Level to Solo Leveling Ranks
  String determineRank(int level) {
    if (level >= 80) return 'Shadow Monarch';
    if (level >= 60) return 'National Rank';
    if (level >= 45) return 'S Rank';
    if (level >= 30) return 'A Rank';
    if (level >= 20) return 'B Rank';
    if (level >= 12) return 'C Rank';
    if (level >= 5) return 'D Rank';
    return 'E Rank';
  }

  /// Determines title dynamically
  String determineTitle(String rank) {
    switch (rank) {
      case 'Shadow Monarch': return 'The Ultimate Ruler';
      case 'National Rank': return 'World Class Hunter';
      case 'S Rank': return 'Apex Sovereign';
      case 'A Rank': return 'Elite Vanguard';
      case 'B Rank': return 'Vanguard Guardian';
      case 'C Rank': return 'Dungeon Veteran';
      case 'D Rank': return 'Novice Raider';
      default: return 'Fledgling Recruit';
    }
  }

  /// Calculate stats dynamically based on current levels & attributes
  Map<String, int> calculateStats(int level, int completedWorkouts, int completedTasks, int waterStreak) {
    return {
      'Strength': 10 + (level * 2) + (completedWorkouts * 3),
      'Vitality': 10 + (level * 2) + (waterStreak * 2),
      'Intelligence': 10 + (level * 1) + (completedTasks * 1),
      'Discipline': 10 + (completedTasks * 2),
      'Stamina': 10 + (level * 1) + (completedWorkouts * 2),
      'Nutrition': 10 + (completedWorkouts + waterStreak),
      'Focus': 10 + (completedTasks * 2),
      'Consistency': 10 + (waterStreak * 3),
    };
  }
}
