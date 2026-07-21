import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/system_state_provider.dart';
import '../widgets/glass_widgets.dart';

class TrainingCenterScreen extends StatefulWidget {
  const TrainingCenterScreen({super.key});

  @override
  State<TrainingCenterScreen> createState() => _TrainingCenterScreenState();
}

class _TrainingCenterScreenState extends State<TrainingCenterScreen> {
  final _durationController = TextEditingController(text: '30');
  final _caloriesController = TextEditingController(text: '240');
  final _notesController = TextEditingController();
  String _workoutType = 'Gym Training';

  void _showAddWorkoutModal() {
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
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'LOG TRAINING DUNGEON CLEAR',
                    style: TextStyle(
                      fontFamily: 'Montserrat',
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 20),
                  DropdownButtonFormField<String>(
                    value: _workoutType,
                    dropdownColor: ThemeColors.background,
                    style: const TextStyle(color: Colors.white),
                    decoration: const InputDecoration(labelText: 'Combat / Training Class', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                    items: ['Gym Training', 'Home Workouts', 'Running / Speed Gate', 'Walking / Stamina Routine', 'Stretching']
                        .map((m) => DropdownMenuItem(value: m, child: Text(m)))
                        .toList(),
                    onChanged: (val) {
                      if (val != null) setState(() => _workoutType = val);
                    },
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                        child: TextField(
                          controller: _durationController,
                          keyboardType: TextInputType.number,
                          style: const TextStyle(color: Colors.white),
                          decoration: const InputDecoration(labelText: 'Combat duration (mins)', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: TextField(
                          controller: _caloriesController,
                          keyboardType: TextInputType.number,
                          style: const TextStyle(color: Colors.white),
                          decoration: const InputDecoration(labelText: 'Energy Spent (kcal)', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _notesController,
                    style: const TextStyle(color: Colors.white),
                    decoration: const InputDecoration(
                      labelText: 'Combat observations / Quest notes',
                      labelStyle: TextStyle(color: ThemeColors.textSecondary),
                    ),
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
                          final duration = int.tryParse(_durationController.text) ?? 30;
                          final calories = int.tryParse(_caloriesController.text) ?? 240;
                          final notes = _notesController.text.trim();

                          final provider = Provider.of<SystemStateProvider>(context, listen: false);
                          await provider.logWorkout(_workoutType, duration, calories, notes);
                          _notesController.clear();
                          if (mounted) Navigator.pop(context);
                        },
                        child: const Text('LOG TRAINING', style: TextStyle(color: Color(0xFF00363a), fontWeight: FontWeight.bold)),
                      ),
                    ],
                  ),
                  const SizedBox(height: 30),
                ],
              ),
            );
          },
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<SystemStateProvider>(context);
    final workouts = provider.todayWorkouts;

    return Scaffold(
      backgroundColor: ThemeColors.background,
      appBar: AppBar(
        title: const Text('TRAINING CENTER', style: TextStyle(fontFamily: 'Montserrat', fontWeight: FontWeight.bold)),
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
                    'DAILY TRAINING CHAMBER',
                    style: TextStyle(
                      fontFamily: 'JetBrains Mono',
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                      color: ThemeColors.primaryBlue,
                    ),
                  ),
                  SizedBox(height: 8),
                  Text(
                    'Leveling requires consistent muscular hyper-adaptation. Log your custom physical trainings and raids here.',
                    style: TextStyle(color: ThemeColors.textSecondary, fontSize: 13),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  'COMPLETED TRAINING LOGS',
                  style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
                ),
                ElevatedButton.icon(
                  style: ElevatedButton.styleFrom(backgroundColor: ThemeColors.primaryBlue),
                  onPressed: _showAddWorkoutModal,
                  icon: const Icon(Icons.fitness_center, color: Color(0xFF00363a), size: 16),
                  label: const Text('LOG CHAMBER', style: TextStyle(color: Color(0xFF00363a), fontWeight: FontWeight.bold)),
                ),
              ],
            ),
            const SizedBox(height: 12),
            if (workouts.isEmpty)
              const GlassCard(
                child: Center(
                  child: Text('No physical active sessions recorded today.', style: TextStyle(color: ThemeColors.textSecondary)),
                ),
              )
            else
              ...workouts.map((w) => Container(
                    margin: const EdgeInsets.only(bottom: 8),
                    child: GlassCard(
                      child: Row(
                        children: [
                          Icon(Icons.directions_run, color: ThemeColors.accentGreen, size: 24),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(w.workoutType, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 15)),
                                Text('Duration: ${w.durationMinutes}m • Expended: ${w.caloriesBurned} kcal', style: const TextStyle(color: ThemeColors.textSecondary, fontSize: 12)),
                                if (w.notes != null && w.notes!.isNotEmpty)
                                  Text('Notes: ${w.notes}', style: const TextStyle(color: ThemeColors.textSecondary, fontSize: 11, fontStyle: FontStyle.italic)),
                              ],
                            ),
                          ),
                          IconButton(
                            icon: const Icon(Icons.delete_outline, color: Colors.redAccent, size: 18),
                            onPressed: () => provider.deleteWorkoutLog(w.id!),
                          ),
                        ],
                      ),
                    ),
                  )),
            const SizedBox(height: 24),
            const Text(
              'RECOVERY SLEEP HYPNOLOGY',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            GlassCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('SLEEP PROTOCOL STATUS', style: TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.primaryBlue, fontSize: 12, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Last Bedtime recorded', style: TextStyle(color: Colors.white, fontSize: 14)),
                      Text(
                        provider.sleepHistory.isNotEmpty ? provider.sleepHistory.first.bedtime : '22:00',
                        style: const TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.accentPurple, fontWeight: FontWeight.bold),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Last Sleep duration', style: TextStyle(color: Colors.white, fontSize: 14)),
                      Text(
                        provider.sleepHistory.isNotEmpty ? '${provider.sleepHistory.first.durationHours}h' : '8h',
                        style: const TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.accentGreen, fontWeight: FontWeight.bold),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  ElevatedButton(
                    style: ElevatedButton.styleFrom(backgroundColor: ThemeColors.primaryBlue.withOpacity(0.2)),
                    onPressed: () {
                      provider.logSleep('22:15', '06:00', 7.75, 88);
                      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Overnight sleep recovery protocol stored.'), backgroundColor: ThemeColors.accentGreen));
                    },
                    child: const Text('Simulate Overnight Log (10pm - 6am)', style: TextStyle(color: ThemeColors.primaryBlue)),
                  )
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
