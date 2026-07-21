class WaterIntake {
  final int? id;
  final String date;
  final int amountMl;
  final String timestamp;

  WaterIntake({
    this.id,
    required this.date,
    required this.amountMl,
    required this.timestamp,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'date': date,
      'amount_ml': amountMl,
      'timestamp': timestamp,
    };
  }

  factory WaterIntake.fromMap(Map<String, dynamic> map) {
    return WaterIntake(
      id: map['id'],
      date: map['date'],
      amountMl: map['amount_ml'] ?? 0,
      timestamp: map['timestamp'] ?? '',
    );
  }
}
