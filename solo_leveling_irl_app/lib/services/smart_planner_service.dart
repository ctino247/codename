import '../models/task_model.dart';

class SmartPlannerService {
  /// Generates a complete disciplined daily timeline integrating wake-up, routine, meals, sleep targets,
  /// and custom user tasks efficiently.
  List<TaskModel> generateSmartSchedule({
    required String date,
    required String wakeTime, // e.g. "06:00"
    required String sleepTime, // e.g. "22:00"
    required List<TaskModel> customTasks,
    List<String> workoutPreference = const [],
  }) {
    final List<TaskModel> schedule = [];

    // 1. Helper to convert HH:MM to minutes from midnight
    int toMins(String timeStr) {
      final parts = timeStr.split(':');
      if (parts.length < 2) return 0;
      return (int.tryParse(parts[0]) ?? 0) * 60 + (int.tryParse(parts[1]) ?? 0);
    }

    String toTimeStr(int mins) {
      final h = (mins ~/ 60) % 24;
      final m = mins % 60;
      return '${h.toString().padLeft(2, '0')}:${m.toString().padLeft(2, '0')}';
    }

    final int startMins = toMins(wakeTime);
    final int endMins = toMins(sleepTime);

    int currentCursor = startMins;

    // 1. Wake up and Morning routine (30 mins)
    schedule.add(TaskModel(
      title: "Wake up & Morning Routine",
      description: "Hydrate, stretch, prepare for the day.",
      durationMinutes: 30,
      priority: 'High',
      category: 'Routine',
      scheduledTime: toTimeStr(currentCursor),
      date: date,
    ));
    currentCursor += 30;

    // 2. Breakfast (30 mins)
    schedule.add(TaskModel(
      title: "Breakfast Feast",
      description: "Replenish nutrients.",
      durationMinutes: 30,
      priority: 'Medium',
      category: 'Nutrition',
      scheduledTime: toTimeStr(currentCursor),
      date: date,
    ));
    currentCursor += 30;

    // 3. Integrate custom tasks
    // Sort custom tasks by Priority (High, then Medium, then Low)
    final sortedCustoms = List<TaskModel>.from(customTasks);
    sortedCustoms.sort((a, b) {
      const priorityOrder = {'High': 0, 'Medium': 1, 'Low': 2};
      final orderA = priorityOrder[a.priority] ?? 1;
      final orderB = priorityOrder[b.priority] ?? 1;
      return orderA.compareTo(orderB);
    });

    for (var task in sortedCustoms) {
      // Check if task fits before sleep
      if (currentCursor + task.durationMinutes <= endMins - 120) { // leave 2 hrs for dinner & night routine
        schedule.add(task.copyWith(
          scheduledTime: toTimeStr(currentCursor),
          category: 'Custom Task',
        ));
        currentCursor += task.durationMinutes;

        // Add short break if time permits
        if (currentCursor + 15 < endMins) {
          schedule.add(TaskModel(
            title: "Short Tactical Break",
            description: "Recover mana.",
            durationMinutes: 15,
            priority: 'Low',
            category: 'Rest',
            scheduledTime: toTimeStr(currentCursor),
            date: date,
          ));
          currentCursor += 15;
        }
      } else {
        // Suggest carrying over or reschedule next day
        schedule.add(task.copyWith(
          scheduledTime: "Rescheduled",
          description: "Carry over due to timing overflow.",
        ));
      }
    }

    // 4. Workout Session (60 mins) if cursor allows, else fit earlier
    int workoutTime = endMins - 120; // 2 hrs before bed
    if (workoutTime > currentCursor) {
      currentCursor = workoutTime;
    }
    schedule.add(TaskModel(
      title: "Daily Dungeon Sweep (Workout)",
      description: "Push limitations and gain strength.",
      durationMinutes: 60,
      priority: 'High',
      category: 'Exercise',
      scheduledTime: toTimeStr(currentCursor),
      date: date,
    ));
    currentCursor += 60;

    // 5. Dinner (30 mins)
    schedule.add(TaskModel(
      title: "Evening Feast (Dinner)",
      description: "Protein and recovery carbs.",
      durationMinutes: 30,
      priority: 'Medium',
      category: 'Nutrition',
      scheduledTime: toTimeStr(currentCursor),
      date: date,
    ));
    currentCursor += 30;

    // 6. Night Routine & Sleep prep (30 mins)
    schedule.add(TaskModel(
      title: "Night Routine & Sleep Prep",
      description: "Decompress and prepare for recovery.",
      durationMinutes: 30,
      priority: 'Medium',
      category: 'Sleep Prep',
      scheduledTime: toTimeStr(currentCursor),
      date: date,
    ));

    return schedule;
  }
}
