import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/system_state_provider.dart';
import '../widgets/glass_widgets.dart';

class SystemSettingsScreen extends StatefulWidget {
  const SystemSettingsScreen({super.key});

  @override
  State<SystemSettingsScreen> createState() => _SystemSettingsScreenState();
}

class _SystemSettingsScreenState extends State<SystemSettingsScreen> {
  bool _animeVoice = true;
  bool _soundReminders = true;
  String _frequency = 'Normal';
  String _weightUnit = 'kg';

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<SystemStateProvider>(context);

    return Scaffold(
      backgroundColor: ThemeColors.background,
      appBar: AppBar(
        title: const Text('SYSTEM CONFIGURATIONS', style: TextStyle(fontFamily: 'Montserrat', fontWeight: FontWeight.bold)),
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
                    'CORE COMPILERS',
                    style: TextStyle(
                      fontFamily: 'JetBrains Mono',
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                      color: ThemeColors.primaryBlue,
                    ),
                  ),
                  SizedBox(height: 8),
                  Text(
                    'Manage localized variables, soundscapes, data exports, units and structural system modes offline.',
                    style: TextStyle(color: ThemeColors.textSecondary, fontSize: 13),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'SOUNDSCAPE CONFIGURATIONS',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            GlassCard(
              child: Column(
                children: [
                  SwitchListTile(
                    title: const Text('Anime Voice Assistant Feedback', style: TextStyle(color: Colors.white)),
                    subtitle: const Text('Simulates anime-inspired tactical prompts audibly.', style: TextStyle(color: ThemeColors.textSecondary)),
                    value: _animeVoice,
                    activeColor: ThemeColors.primaryBlue,
                    onChanged: (val) => setState(() => _animeVoice = val),
                  ),
                  const Divider(color: ThemeColors.glassBorder),
                  SwitchListTile(
                    title: const Text('Critical Reminder Soundscapes', style: TextStyle(color: Colors.white)),
                    subtitle: const Text('Triggers notifications using synthesized theme prompts.', style: TextStyle(color: ThemeColors.textSecondary)),
                    value: _soundReminders,
                    activeColor: ThemeColors.accentPurple,
                    onChanged: (val) => setState(() => _soundReminders = val),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'SYSTEM SCALES & METRICS',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            GlassCard(
              child: Column(
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Weight Scaling unit', style: TextStyle(color: Colors.white)),
                      DropdownButton<String>(
                        value: _weightUnit,
                        dropdownColor: ThemeColors.background,
                        style: const TextStyle(color: Colors.white),
                        underline: Container(),
                        items: ['kg', 'lbs'].map((u) => DropdownMenuItem(value: u, child: Text(u))).toList(),
                        onChanged: (val) {
                          if (val != null) setState(() => _weightUnit = val);
                        },
                      ),
                    ],
                  ),
                  const Divider(color: ThemeColors.glassBorder),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Vocal Notification Frequency', style: TextStyle(color: Colors.white)),
                      DropdownButton<String>(
                        value: _frequency,
                        dropdownColor: ThemeColors.background,
                        style: const TextStyle(color: Colors.white),
                        underline: Container(),
                        items: ['None', 'Light', 'Normal', 'Discipline Master']
                            .map((u) => DropdownMenuItem(value: u, child: Text(u)))
                            .toList(),
                        onChanged: (val) {
                          if (val != null) setState(() => _frequency = val);
                        },
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'LOCAL STORAGE CONTROL (PRIVACY FIRST)',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            GlassCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  const Text(
                    'No central servers store your logs. Everything persists inside local devices securely.',
                    style: TextStyle(color: ThemeColors.textSecondary, fontSize: 13),
                  ),
                  const SizedBox(height: 16),
                  ElevatedButton.icon(
                    style: ElevatedButton.styleFrom(backgroundColor: ThemeColors.primaryBlue.withOpacity(0.15)),
                    onPressed: () async {
                      final str = await provider.exportData();
                      if (mounted) {
                        showDialog(
                          context: context,
                          builder: (context) => AlertDialog(
                            backgroundColor: ThemeColors.background,
                            title: const Text('LOCAL EXPORT DATA', style: TextStyle(color: Colors.white, fontFamily: 'Montserrat')),
                            content: SingleChildScrollView(
                              child: SelectableText(str, style: const TextStyle(color: ThemeColors.textSecondary, fontSize: 12, fontFamily: 'JetBrains Mono')),
                            ),
                            actions: [
                              TextButton(onPressed: () => Navigator.pop(context), child: const Text('DISMISS')),
                            ],
                          ),
                        );
                      }
                    },
                    icon: const Icon(Icons.download, color: ThemeColors.primaryBlue),
                    label: const Text('EXPORT LOCAL DATABASE BACKUP', style: TextStyle(color: ThemeColors.primaryBlue)),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 40),
          ],
        ),
      ),
    );
  }
}
