import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/system_state_provider.dart';
import '../widgets/glass_widgets.dart';

class DailyScheduleTimelineScreen extends StatefulWidget {
  const DailyScheduleTimelineScreen({super.key});

  @override
  State<DailyScheduleTimelineScreen> createState() => _DailyScheduleTimelineScreenState();
}

class _DailyScheduleTimelineScreenState extends State<DailyScheduleTimelineScreen> {
  final _taskTitleController = TextEditingController();
  int _taskDuration = 30;
  String _taskPriority = 'Medium';
  String _taskCategory = 'Work';

  void _showAddTaskModal() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: ThemeColors.background,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            return Padding(
              padding: EdgeInsets.only(
                bottom: MediaQuery.of(context).viewInsets.bottom,
                left: 24,
                right: 24,
                top: 24,
              ),
              child: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'ADD CUSTOM QUEST / OBJECTIVE',
                      style: TextStyle(
                        fontFamily: 'Montserrat',
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                    const SizedBox(height: 20),
                    TextField(
                      controller: _taskTitleController,
                      style: const TextStyle(color: Colors.white),
                      decoration: const InputDecoration(
                        labelText: 'Quest Name / Task Title',
                        labelStyle: TextStyle(color: ThemeColors.textSecondary),
                        enabledBorder: UnderlineInputBorder(borderSide: BorderSide(color: ThemeColors.glassBorder)),
                        focusedBorder: UnderlineInputBorder(borderSide: BorderSide(color: ThemeColors.primaryBlue)),
                      ),
                    ),
                    const SizedBox(height: 20),
                    Row(
                      children: [
                        Expanded(
                          child: DropdownButtonFormField<int>(
                            value: _taskDuration,
                            dropdownColor: ThemeColors.background,
                            style: const TextStyle(color: Colors.white),
                            decoration: const InputDecoration(labelText: 'Duration (mins)', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                            items: [15, 30, 45, 60, 90, 120, 180, 240, 360]
                                .map((m) => DropdownMenuItem(value: m, child: Text('$m mins')))
                                .toList(),
                            onChanged: (val) {
                              if (val != null) setModalState(() => _taskDuration = val);
                            },
                          ),
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: DropdownButtonFormField<String>(
                            value: _taskPriority,
                            dropdownColor: ThemeColors.background,
                            style: const TextStyle(color: Colors.white),
                            decoration: const InputDecoration(labelText: 'Priority Tier', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                            items: ['High', 'Medium', 'Low']
                                .map((m) => DropdownMenuItem(value: m, child: Text(m)))
                                .toList(),
                            onChanged: (val) {
                              if (val != null) setModalState(() => _taskPriority = val);
                            },
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 20),
                    DropdownButtonFormField<String>(
                      value: _taskCategory,
                      dropdownColor: ThemeColors.background,
                      style: const TextStyle(color: Colors.white),
                      decoration: const InputDecoration(labelText: 'Quest Category', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                      items: ['Work', 'Gym', 'Reading', 'Water Reminder', 'Breaks', 'Custom']
                          .map((m) => DropdownMenuItem(value: m, child: Text(m)))
                          .toList(),
                      onChanged: (val) {
                        if (val != null) setModalState(() => _taskCategory = val);
                      },
                    ),
                    const SizedBox(height: 30),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.end,
                      children: [
                        TextButton(
                          onPressed: () => Navigator.pop(context),
                          child: const Text('CANCEL', style: TextStyle(color: Colors.red)),
                        ),
                        const SizedBox(width: 16),
                        ElevatedButton(
                          style: ElevatedButton.styleFrom(backgroundColor: ThemeColors.primaryBlue),
                          onPressed: () async {
                            final title = _taskTitleController.text.trim();
                            if (title.isNotEmpty) {
                              final provider = Provider.of<SystemStateProvider>(context, listen: false);
                              await provider.addTask(title, _taskDuration, _taskPriority, _taskCategory);
                              _taskTitleController.clear();
                              if (mounted) Navigator.pop(context);
                            }
                          },
                          child: const Text('ENGAGE QUEST', style: TextStyle(color: Color(0xFF00363a), fontWeight: FontWeight.bold)),
                        ),
                      ],
                    ),
                    const SizedBox(height: 30),
                  ],
                ),
              ),
            );
          },
        );
      },
    );
  }

  void _triggerSmartCompilation() async {
    final provider = Provider.of<SystemStateProvider>(context, listen: false);
    await provider.triggerSmartPlanner(provider.todayTasks);
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Chronological alignment generated successfully!'),
          backgroundColor: ThemeColors.accentGreen,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<SystemStateProvider>(context);
    final tasks = provider.todayTasks;

    return Scaffold(
      backgroundColor: ThemeColors.background,
      appBar: AppBar(
        title: const Text('DAILY TIMELINE', style: TextStyle(fontFamily: 'Montserrat', fontWeight: FontWeight.bold)),
        backgroundColor: Colors.transparent,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.flash_on, color: ThemeColors.primaryBlue),
            tooltip: 'Regenerate Smart Schedule',
            onPressed: _triggerSmartCompilation,
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: GlassCard(
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'PLANNER SYSTEM ACTIVE',
                        style: TextStyle(color: ThemeColors.accentGreen, fontFamily: 'JetBrains Mono', fontSize: 12, fontWeight: FontWeight.bold),
                      ),
                      SizedBox(height: 4),
                      Text(
                        'Smart Chronological Alignment',
                        style: TextStyle(color: Colors.white, fontSize: 14),
                      ),
                    ],
                  ),
                  ElevatedButton.icon(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: ThemeColors.primaryBlue,
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                    onPressed: _showAddTaskModal,
                    icon: const Icon(Icons.add, color: Color(0xFF00363a), size: 18),
                    label: const Text('QUEST', style: TextStyle(color: Color(0xFF00363a), fontWeight: FontWeight.bold)),
                  ),
                ],
              ),
            ),
          ),
          Expanded(
            child: tasks.isEmpty
                ? const Center(
                    child: Text(
                      'No dungeon objectives planned. Tap "QUEST" to add.',
                      style: TextStyle(color: ThemeColors.textSecondary),
                    ),
                  )
                : ListView.builder(
                    itemCount: tasks.length,
                    padding: const EdgeInsets.symmetric(horizontal: 16),
                    itemBuilder: (context, index) {
                      final t = tasks[index];
                      Color priorityColor = ThemeColors.textSecondary;
                      if (t.priority == 'High') priorityColor = Colors.redAccent;
                      if (t.priority == 'Medium') priorityColor = ThemeColors.primaryBlue;

                      return Container(
                        margin: const EdgeInsets.only(bottom: 12),
                        child: GlassCard(
                          glowColor: t.isCompleted ? ThemeColors.accentGreen : null,
                          child: Row(
                            children: [
                              Checkbox(
                                activeColor: ThemeColors.accentGreen,
                                checkColor: Colors.black,
                                value: t.isCompleted,
                                onChanged: (value) => provider.toggleTask(t),
                              ),
                              const SizedBox(width: 8),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      t.title,
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontWeight: FontWeight.bold,
                                        fontSize: 16,
                                        decoration: t.isCompleted ? TextDecoration.lineThrough : null,
                                      ),
                                    ),
                                    const SizedBox(height: 4),
                                    Row(
                                      children: [
                                        if (t.scheduledTime != null) ...[
                                          Icon(Icons.access_time, size: 12, color: ThemeColors.textSecondary),
                                          const SizedBox(width: 4),
                                          Text(
                                            t.scheduledTime!,
                                            style: const TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.textSecondary, fontSize: 12),
                                          ),
                                          const SizedBox(width: 12),
                                        ],
                                        Text(
                                          '${t.durationMinutes}m',
                                          style: const TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.textSecondary, fontSize: 12),
                                        ),
                                        const SizedBox(width: 12),
                                        Container(
                                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                          decoration: BoxDecoration(
                                            border: Border.all(color: priorityColor, width: 0.5),
                                            borderRadius: BorderRadius.circular(4),
                                          ),
                                          child: Text(
                                            t.priority,
                                            style: TextStyle(color: priorityColor, fontSize: 10, fontWeight: FontWeight.bold),
                                          ),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                              ),
                              IconButton(
                                icon: const Icon(Icons.delete_outline, color: Colors.redAccent, size: 20),
                                onPressed: () => provider.removeTask(t.id!),
                              ),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
          ),
        ],
      ),
    );
  }
}
