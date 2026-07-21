class AppSettings {
  final int? id;
  final String wakeTime;
  final String sleepTime;
  final bool reminderSounds;
  final bool animeVoice;
  final String notificationFrequency;
  final String weightUnit;
  final String distanceUnit;
  final bool darkMode;

  AppSettings({
    this.id,
    this.wakeTime = '06:00',
    this.sleepTime = '22:00',
    this.reminderSounds = true,
    this.animeVoice = true,
    this.notificationFrequency = 'Normal',
    this.weightUnit = 'kg',
    this.distanceUnit = 'km',
    this.darkMode = true,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'wake_time': wakeTime,
      'sleep_time': sleepTime,
      'reminder_sounds': reminderSounds ? 1 : 0,
      'anime_voice': animeVoice ? 1 : 0,
      'notification_frequency': notificationFrequency,
      'weight_unit': weightUnit,
      'distance_unit': distanceUnit,
      'dark_mode': darkMode ? 1 : 0,
    };
  }

  factory AppSettings.fromMap(Map<String, dynamic> map) {
    return AppSettings(
      id: map['id'],
      wakeTime: map['wake_time'] ?? '06:00',
      sleepTime: map['sleep_time'] ?? '22:00',
      reminderSounds: (map['reminder_sounds'] ?? 1) == 1,
      animeVoice: (map['anime_voice'] ?? 1) == 1,
      notificationFrequency: map['notification_frequency'] ?? 'Normal',
      weightUnit: map['weight_unit'] ?? 'kg',
      distanceUnit: map['distance_unit'] ?? 'km',
      darkMode: (map['dark_mode'] ?? 1) == 1,
    );
  }
}
