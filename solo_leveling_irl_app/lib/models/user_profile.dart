class UserProfile {
  final int? id;
  final String name;
  final int level;
  final int xp;
  final String rank;
  final int streak;
  final int dayCount;
  final String challengeStartDate;
  final int? age;
  final double? height;
  final double? weight;
  final double? targetWeight;
  final String? targetDate;
  final String? gender;
  final String? activityLevel;
  final String? workoutPreference;
  final String? foodPreferences;
  final int? calorieGoal;
  final int? proteinGoal;
  final int? waterGoal;

  UserProfile({
    this.id,
    required this.name,
    this.level = 1,
    this.xp = 0,
    this.rank = 'E Rank',
    this.streak = 0,
    this.dayCount = 1,
    required this.challengeStartDate,
    this.age,
    this.height,
    this.weight,
    this.targetWeight,
    this.targetDate,
    this.gender,
    this.activityLevel,
    this.workoutPreference,
    this.foodPreferences,
    this.calorieGoal,
    this.proteinGoal,
    this.waterGoal,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
      'level': level,
      'xp': xp,
      'rank': rank,
      'streak': streak,
      'day_count': dayCount,
      'challenge_start_date': challengeStartDate,
      'age': age,
      'height': height,
      'weight': weight,
      'target_weight': targetWeight,
      'target_date': targetDate,
      'gender': gender,
      'activity_level': activityLevel,
      'workout_preference': workoutPreference,
      'food_preferences': foodPreferences,
      'calorie_goal': calorieGoal,
      'protein_goal': proteinGoal,
      'water_goal': waterGoal,
    };
  }

  factory UserProfile.fromMap(Map<String, dynamic> map) {
    return UserProfile(
      id: map['id'],
      name: map['name'],
      level: map['level'] ?? 1,
      xp: map['xp'] ?? 0,
      rank: map['rank'] ?? 'E Rank',
      streak: map['streak'] ?? 0,
      dayCount: map['day_count'] ?? 1,
      challengeStartDate: map['challenge_start_date'],
      age: map['age'],
      height: map['height'] != null ? (map['height'] as num).toDouble() : null,
      weight: map['weight'] != null ? (map['weight'] as num).toDouble() : null,
      targetWeight: map['target_weight'] != null ? (map['target_weight'] as num).toDouble() : null,
      targetDate: map['target_date'],
      gender: map['gender'],
      activityLevel: map['activity_level'],
      workoutPreference: map['workout_preference'],
      foodPreferences: map['food_preferences'],
      calorieGoal: map['calorie_goal'],
      proteinGoal: map['protein_goal'],
      waterGoal: map['water_goal'],
    );
  }

  UserProfile copyWith({
    int? id,
    String? name,
    int? level,
    int? xp,
    String? rank,
    int? streak,
    int? dayCount,
    String? challengeStartDate,
    int? age,
    double? height,
    double? weight,
    double? targetWeight,
    String? targetDate,
    String? gender,
    String? activityLevel,
    String? workoutPreference,
    String? foodPreferences,
    int? calorieGoal,
    int? proteinGoal,
    int? waterGoal,
  }) {
    return UserProfile(
      id: id ?? this.id,
      name: name ?? this.name,
      level: level ?? this.level,
      xp: xp ?? this.xp,
      rank: rank ?? this.rank,
      streak: streak ?? this.streak,
      dayCount: dayCount ?? this.dayCount,
      challengeStartDate: challengeStartDate ?? this.challengeStartDate,
      age: age ?? this.age,
      height: height ?? this.height,
      weight: weight ?? this.weight,
      targetWeight: targetWeight ?? this.targetWeight,
      targetDate: targetDate ?? this.targetDate,
      gender: gender ?? this.gender,
      activityLevel: activityLevel ?? this.activityLevel,
      workoutPreference: workoutPreference ?? this.workoutPreference,
      foodPreferences: foodPreferences ?? this.foodPreferences,
      calorieGoal: calorieGoal ?? this.calorieGoal,
      proteinGoal: proteinGoal ?? this.proteinGoal,
      waterGoal: waterGoal ?? this.waterGoal,
    );
  }
}
