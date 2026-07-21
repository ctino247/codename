class ReminderModel {
  final int? id;
  final String title;
  final String time;
  final bool isActive;
  final String type; // water, workout, wake, sleep, custom

  ReminderModel({
    this.id,
    required this.title,
    required this.time,
    required this.isActive,
    required this.type,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'title': title,
      'time': time,
      'is_active': isActive ? 1 : 0,
      'type': type,
    };
  }

  factory ReminderModel.fromMap(Map<String, dynamic> map) {
    return ReminderModel(
      id: map['id'],
      title: map['title'] ?? '',
      time: map['time'] ?? '12:00',
      isActive: (map['is_active'] ?? 1) == 1,
      type: map['type'] ?? 'custom',
    );
  }
}
