class SleepLog {
  final int? id;
  final String date;
  final String bedtime;
  final String wakeTime;
  final double durationHours;
  final int qualityScore; // 1 to 100

  SleepLog({
    this.id,
    required this.date,
    required this.bedtime,
    required this.wakeTime,
    required this.durationHours,
    required this.qualityScore,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'date': date,
      'bedtime': bedtime,
      'wake_time': wakeTime,
      'duration_hours': durationHours,
      'quality_score': qualityScore,
    };
  }

  factory SleepLog.fromMap(Map<String, dynamic> map) {
    return SleepLog(
      id: map['id'],
      date: map['date'],
      bedtime: map['bedtime'] ?? '22:00',
      wakeTime: map['wake_time'] ?? '06:00',
      durationHours: (map['duration_hours'] ?? 0.0) is int
          ? (map['duration_hours'] as int).toDouble()
          : (map['duration_hours'] ?? 0.0),
      qualityScore: map['quality_score'] ?? 0,
    );
  }
}
