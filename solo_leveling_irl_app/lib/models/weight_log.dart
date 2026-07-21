class WeightLog {
  final int? id;
  final String date;
  final double weight;

  WeightLog({
    this.id,
    required this.date,
    required this.weight,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'date': date,
      'weight': weight,
    };
  }

  factory WeightLog.fromMap(Map<String, dynamic> map) {
    return WeightLog(
      id: map['id'],
      date: map['date'],
      weight: (map['weight'] as num).toDouble(),
    );
  }
}
