class Achievement {
  final int? id;
  final String title;
  final String description;
  final String? unlockedDate;
  final bool isUnlocked;
  final String badgeName;

  Achievement({
    this.id,
    required this.title,
    required this.description,
    this.unlockedDate,
    required this.isUnlocked,
    required this.badgeName,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'unlocked_date': unlockedDate,
      'is_unlocked': isUnlocked ? 1 : 0,
      'badge_name': badgeName,
    };
  }

  factory Achievement.fromMap(Map<String, dynamic> map) {
    return Achievement(
      id: map['id'],
      title: map['title'],
      description: map['description'],
      unlockedDate: map['unlocked_date'],
      isUnlocked: (map['is_unlocked'] ?? 0) == 1,
      badgeName: map['badge_name'] ?? 'general',
    );
  }
}
