class WorkoutModel {
  final int? id;
  final String date;
  final String workoutType;
  final int durationMinutes;
  final int caloriesBurned;
  final String? notes;

  WorkoutModel({
    this.id,
    required this.date,
    required this.workoutType,
    required this.durationMinutes,
    required this.caloriesBurned,
    this.notes,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'date': date,
      'workout_type': workoutType,
      'duration_minutes': durationMinutes,
      'calories_burned': caloriesBurned,
      'notes': notes,
    };
  }

  factory WorkoutModel.fromMap(Map<String, dynamic> map) {
    return WorkoutModel(
      id: map['id'],
      date: map['date'],
      workoutType: map['workout_type'] ?? 'General',
      durationMinutes: map['duration_minutes'] ?? 0,
      caloriesBurned: map['calories_burned'] ?? 0,
      notes: map['notes'],
    );
  }
}
