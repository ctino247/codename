import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/system_state_provider.dart';
import '../widgets/glass_widgets.dart';

class HunterJournalScreen extends StatefulWidget {
  const HunterJournalScreen({super.key});

  @override
  State<HunterJournalScreen> createState() => _HunterJournalScreenState();
}

class _HunterJournalScreenState extends State<HunterJournalScreen> {
  final _journalController = TextEditingController();
  final _winsController = TextEditingController();
  final _problemsController = TextEditingController();
  String _mood = 'Vigorous';
  int _energyLevel = 8;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final provider = Provider.of<SystemStateProvider>(context, listen: false);
      if (provider.todayJournal != null) {
        setState(() {
          _journalController.text = provider.todayJournal!.entry;
          _winsController.text = provider.todayJournal!.wins ?? '';
          _problemsController.text = provider.todayJournal!.problems ?? '';
          _mood = provider.todayJournal!.mood ?? 'Vigorous';
          _energyLevel = provider.todayJournal!.energyLevel ?? 8;
        });
      }
    });
  }

  void _saveReview() async {
    final entry = _journalController.text.trim();
    final wins = _winsController.text.trim();
    final problems = _problemsController.text.trim();

    if (entry.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please log details inside the entry field.'), backgroundColor: Colors.redAccent),
      );
      return;
    }

    final provider = Provider.of<SystemStateProvider>(context, listen: false);
    await provider.logJournal(entry, _mood, _energyLevel, wins, problems);

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Daily dungeon feedback recorded. Future recommendations balanced.'), backgroundColor: ThemeColors.accentGreen),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ThemeColors.background,
      appBar: AppBar(
        title: const Text('HUNTER\'S JOURNAL', style: TextStyle(fontFamily: 'Montserrat', fontWeight: FontWeight.bold)),
        backgroundColor: Colors.transparent,
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const GlassCard(
              glowColor: ThemeColors.accentPurple,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'NIGHT DUNGEON REVIEW',
                    style: TextStyle(
                      fontFamily: 'JetBrains Mono',
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                      color: ThemeColors.accentPurple,
                    ),
                  ),
                  SizedBox(height: 8),
                  Text(
                    'Evaluate today\'s raid successes and metrics. High-level planning algorithms adapt next morning\'s layouts from these analytics.',
                    style: TextStyle(color: ThemeColors.textSecondary, fontSize: 13),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'DAILY REFLECTION & OBSERVATIONS',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            GlassCard(
              child: Column(
                children: [
                  TextField(
                    controller: _journalController,
                    maxLines: 4,
                    style: const TextStyle(color: Colors.white),
                    decoration: InputDecoration(
                      hintText: 'Record overall progress, mental breakthroughs, ideas...',
                      hintStyle: TextStyle(color: Colors.white.withOpacity(0.3)),
                      border: InputBorder.none,
                    ),
                  ),
                  const Divider(color: ThemeColors.glassBorder),
                  Row(
                    children: [
                      const Text('Mood:', style: TextStyle(color: ThemeColors.textSecondary, fontSize: 14)),
                      const SizedBox(width: 12),
                      Expanded(
                        child: DropdownButton<String>(
                          value: _mood,
                          dropdownColor: ThemeColors.background,
                          style: const TextStyle(color: Colors.white),
                          isExpanded: true,
                          underline: Container(),
                          items: ['Vigorous', 'Tired', 'Focused', 'Stressed', 'Exhausted']
                              .map((m) => DropdownMenuItem(value: m, child: Text(m)))
                              .toList(),
                          onChanged: (val) {
                            if (val != null) setState(() => _mood = val);
                          },
                        ),
                      ),
                    ],
                  ),
                  Row(
                    children: [
                      const Text('Energy Level:', style: TextStyle(color: ThemeColors.textSecondary, fontSize: 14)),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Slider(
                          value: _energyLevel.toDouble(),
                          min: 1,
                          max: 10,
                          divisions: 9,
                          activeColor: ThemeColors.primaryBlue,
                          onChanged: (val) => setState(() => _energyLevel = val.round()),
                        ),
                      ),
                      Text('$_energyLevel/10', style: const TextStyle(color: Colors.white, fontFamily: 'JetBrains Mono')),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16),
            const Text(
              'DUNGEON SUCCESSES (WINS)',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            GlassCard(
              child: TextField(
                controller: _winsController,
                maxLines: 2,
                style: const TextStyle(color: Colors.white),
                decoration: InputDecoration(
                  hintText: 'What went exceptionally well today?',
                  hintStyle: TextStyle(color: Colors.white.withOpacity(0.3)),
                  border: InputBorder.none,
                ),
              ),
            ),
            const SizedBox(height: 16),
            const Text(
              'DUNGEON COMPLICATIONS (PROBLEMS)',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            GlassCard(
              child: TextField(
                controller: _problemsController,
                maxLines: 2,
                style: const TextStyle(color: Colors.white),
                decoration: InputDecoration(
                  hintText: 'Identify weak points or barriers faced.',
                  hintStyle: TextStyle(color: Colors.white.withOpacity(0.3)),
                  border: InputBorder.none,
                ),
              ),
            ),
            const SizedBox(height: 32),
            Center(
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: ThemeColors.accentPurple,
                  padding: const EdgeInsets.symmetric(horizontal: 50, vertical: 16),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                onPressed: _saveReview,
                child: const Text(
                  'RECORD DUNGEON DATA',
                  style: TextStyle(
                    fontFamily: 'Montserrat',
                    fontWeight: FontWeight.w900,
                    color: Colors.white,
                    fontSize: 14,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
