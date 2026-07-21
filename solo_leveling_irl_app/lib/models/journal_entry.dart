class JournalEntry {
  final int? id;
  final String date;
  final String entry;
  final String? mood;
  final int? energyLevel;
  final String? wins;
  final String? problems;

  JournalEntry({
    this.id,
    required this.date,
    required this.entry,
    this.mood,
    this.energyLevel,
    this.wins,
    this.problems,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'date': date,
      'entry': entry,
      'mood': mood,
      'energy_level': energyLevel,
      'wins': wins,
      'problems': problems,
    };
  }

  factory JournalEntry.fromMap(Map<String, dynamic> map) {
    return JournalEntry(
      id: map['id'],
      date: map['date'],
      entry: map['entry'] ?? '',
      mood: map['mood'],
      energyLevel: map['energy_level'],
      wins: map['wins'],
      problems: map['problems'],
    );
  }
}
