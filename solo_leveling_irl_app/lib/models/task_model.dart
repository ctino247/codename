class TaskModel {
  final int? id;
  final String title;
  final String? description;
  final int durationMinutes;
  final String priority; // High, Medium, Low
  final String? scheduledTime; // e.g. "08:30"
  final String category; // Wake up, Work, Gym, Reading, etc.
  final bool isCompleted;
  final String date; // YYYY-MM-DD

  TaskModel({
    this.id,
    required this.title,
    this.description,
    this.durationMinutes = 30,
    this.priority = 'Medium',
    this.scheduledTime,
    this.category = 'Custom',
    this.isCompleted = false,
    required this.date,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'duration_minutes': durationMinutes,
      'priority': priority,
      'scheduled_time': scheduledTime,
      'category': category,
      'is_completed': isCompleted ? 1 : 0,
      'date': date,
    };
  }

  factory TaskModel.fromMap(Map<String, dynamic> map) {
    return TaskModel(
      id: map['id'],
      title: map['title'],
      description: map['description'],
      durationMinutes: map['duration_minutes'] ?? 30,
      priority: map['priority'] ?? 'Medium',
      scheduledTime: map['scheduled_time'],
      category: map['category'] ?? 'Custom',
      isCompleted: (map['is_completed'] ?? 0) == 1,
      date: map['date'],
    );
  }

  TaskModel copyWith({
    int? id,
    String? title,
    String? description,
    int? durationMinutes,
    String? priority,
    String? scheduledTime,
    String? category,
    bool? isCompleted,
    String? date,
  }) {
    return TaskModel(
      id: id ?? this.id,
      title: title ?? this.title,
      description: description ?? this.description,
      durationMinutes: durationMinutes ?? this.durationMinutes,
      priority: priority ?? this.priority,
      scheduledTime: scheduledTime ?? this.scheduledTime,
      category: category ?? this.category,
      isCompleted: isCompleted ?? this.isCompleted,
      date: date ?? this.date,
    );
  }
}
