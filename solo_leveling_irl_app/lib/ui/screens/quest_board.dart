import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/system_state_provider.dart';
import '../widgets/glass_widgets.dart';

class QuestBoardScreen extends StatelessWidget {
  const QuestBoardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<SystemStateProvider>(context);
    final tasks = provider.todayTasks;

    // Safe helper to evaluate hour of scheduled time
    int _getHourOfTask(String? scheduledTime) {
      if (scheduledTime == null || scheduledTime == "Rescheduled") return 12; // default safe fallback
      final parts = scheduledTime.split(':');
      if (parts.isEmpty) return 12;
      return int.tryParse(parts[0]) ?? 12;
    }

    // Filter tasks safely by daily periods
    final morningQuests = tasks.where((t) =>
      t.category.toLowerCase().contains('morning') ||
      (t.scheduledTime != null && t.scheduledTime != "Rescheduled" && _getHourOfTask(t.scheduledTime) < 12)
    ).toList();

    final afternoonQuests = tasks.where((t) =>
      t.category.toLowerCase().contains('afternoon') ||
      (t.scheduledTime != null && t.scheduledTime != "Rescheduled" && _getHourOfTask(t.scheduledTime) >= 12 && _getHourOfTask(t.scheduledTime) < 18)
    ).toList();

    final eveningQuests = tasks.where((t) =>
      t.category.toLowerCase().contains('evening') ||
      t.category.toLowerCase().contains('night') ||
      (t.scheduledTime != null && t.scheduledTime != "Rescheduled" && _getHourOfTask(t.scheduledTime) >= 18)
    ).toList();

    // Boss quest is represented by High-Priority custom items
    final bossQuests = tasks.where((t) => t.priority == 'High').toList();

    Widget buildQuestGroup(String title, List<dynamic> list, Color activeColor) {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 4,
                height: 18,
                color: activeColor,
              ),
              const SizedBox(width: 8),
              Text(
                title.toUpperCase(),
                style: const TextStyle(
                  fontFamily: 'Montserrat',
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
              const Spacer(),
              Text(
                '${list.where((q) => q.isCompleted).length}/${list.length} CLEARED',
                style: const TextStyle(fontFamily: 'JetBrains Mono', fontSize: 12, color: ThemeColors.textSecondary),
              ),
            ],
          ),
          const SizedBox(height: 12),
          if (list.isEmpty)
            const Padding(
              padding: EdgeInsets.symmetric(vertical: 8.0),
              child: Text(
                'No quests scheduled in this sector.',
                style: TextStyle(color: ThemeColors.textSecondary, fontSize: 13),
              ),
            )
          else
            ...list.map((q) => Container(
                  margin: const EdgeInsets.only(bottom: 10),
                  child: GlassCard(
                    glowColor: q.isCompleted ? ThemeColors.accentGreen : null,
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                    child: Row(
                      children: [
                        Checkbox(
                          activeColor: ThemeColors.accentGreen,
                          checkColor: Colors.black,
                          value: q.isCompleted,
                          onChanged: (val) => provider.toggleTask(q),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                q.title,
                                style: TextStyle(
                                  color: Colors.white,
                                  fontWeight: FontWeight.bold,
                                  fontSize: 15,
                                  decoration: q.isCompleted ? TextDecoration.lineThrough : null,
                                ),
                              ),
                              if (q.scheduledTime != null)
                                Text(
                                  'Time: ${q.scheduledTime}',
                                  style: const TextStyle(fontFamily: 'JetBrains Mono', fontSize: 11, color: ThemeColors.textSecondary),
                                )
                            ],
                          ),
                        ),
                        Text(
                          '+25 XP',
                          style: TextStyle(fontFamily: 'JetBrains Mono', fontSize: 12, color: activeColor, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                  ),
                )),
          const SizedBox(height: 24),
        ],
      );
    }

    return Scaffold(
      backgroundColor: ThemeColors.background,
      appBar: AppBar(
        title: const Text('QUEST BOARD', style: TextStyle(fontFamily: 'Montserrat', fontWeight: FontWeight.bold)),
        backgroundColor: Colors.transparent,
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const GlassCard(
              glowColor: ThemeColors.primaryBlue,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'DAILY QUEST COMPILATION',
                    style: TextStyle(
                      fontFamily: 'JetBrains Mono',
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                      color: ThemeColors.primaryBlue,
                    ),
                  ),
                  SizedBox(height: 8),
                  Text(
                    'Complete your scheduled objectives. Clearing all sectors rewards premium XP multipliers and limits breakthrough metrics.',
                    style: TextStyle(color: ThemeColors.textSecondary, fontSize: 13),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            buildQuestGroup('Morning Mission Sector', morningQuests, ThemeColors.primaryBlue),
            buildQuestGroup('Afternoon Mission Sector', afternoonQuests, ThemeColors.accentPurple),
            buildQuestGroup('Evening Mission Sector', eveningQuests, ThemeColors.textSecondary),
            buildQuestGroup('BOSS GATES (High Priority)', bossQuests, Colors.redAccent),
          ],
        ),
      ),
    );
  }
}
