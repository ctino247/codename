import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:fl_chart/fl_chart.dart';
import '../../providers/system_state_provider.dart';
import '../widgets/glass_widgets.dart';

class SystemAnalyticsScreen extends StatelessWidget {
  const SystemAnalyticsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<SystemStateProvider>(context);
    final weights = provider.weightHistory;
    final sleeps = provider.sleepHistory;

    return Scaffold(
      backgroundColor: ThemeColors.background,
      appBar: AppBar(
        title: const Text('SYSTEM ANALYTICS', style: TextStyle(fontFamily: 'Montserrat', fontWeight: FontWeight.bold)),
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
                    'BIOMETRIC DATA MATRIX',
                    style: TextStyle(
                      fontFamily: 'JetBrains Mono',
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                      color: ThemeColors.primaryBlue,
                    ),
                  ),
                  SizedBox(height: 8),
                  Text(
                    'Review visual growth trends across weight logs, sleep durations, consistency ratios, and historical XP breaks.',
                    style: TextStyle(color: ThemeColors.textSecondary, fontSize: 13),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'WEIGHT BREAKDOWN TREND (KG)',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            GlassCard(
              height: 250,
              child: weights.isEmpty
                  ? const Center(child: Text('Insufficient weight points recorded.', style: TextStyle(color: ThemeColors.textSecondary)))
                  : LineChart(
                      LineChartData(
                        gridData: const FlGridData(show: false),
                        titlesData: const FlTitlesData(show: false),
                        borderData: FlBorderData(show: false),
                        lineBarsData: [
                          LineChartBarData(
                            spots: List.generate(
                              weights.length,
                              (index) => FlSpot(index.toDouble(), weights[index].weight),
                            ),
                            isCurved: true,
                            color: ThemeColors.primaryBlue,
                            barWidth: 3,
                            dotData: const FlDotData(show: true),
                            belowBarData: BarAreaData(
                              show: true,
                              color: ThemeColors.primaryBlue.withOpacity(0.1),
                            ),
                          ),
                        ],
                      ),
                    ),
            ),
            const SizedBox(height: 24),
            const Text(
              'SLEEP QUALITY ARCHIVES (HOURS)',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            GlassCard(
              height: 250,
              child: sleeps.isEmpty
                  ? const Center(child: Text('Insufficient sleep archives recorded.', style: TextStyle(color: ThemeColors.textSecondary)))
                  : BarChart(
                      BarChartData(
                        gridData: const FlGridData(show: false),
                        titlesData: const FlTitlesData(show: false),
                        borderData: FlBorderData(show: false),
                        barGroups: List.generate(
                          sleeps.length,
                          (index) => BarChartGroupData(
                            x: index,
                            barRods: [
                              BarChartRodData(
                                toY: sleeps[index].durationHours,
                                color: ThemeColors.accentPurple,
                                width: 14,
                                borderRadius: BorderRadius.circular(4),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ),
            ),
            const SizedBox(height: 24),
            const Text(
              'HUNTER ACHIEVEMENTS',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            ...provider.achievements.map((a) => Container(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: GlassCard(
                    glowColor: a.isUnlocked ? ThemeColors.accentGreen : null,
                    child: Row(
                      children: [
                        Icon(
                          a.isUnlocked ? Icons.verified_user : Icons.lock_outline,
                          color: a.isUnlocked ? ThemeColors.accentGreen : ThemeColors.textSecondary,
                          size: 24,
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(a.title, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14)),
                              Text(a.description, style: const TextStyle(color: ThemeColors.textSecondary, fontSize: 12)),
                            ],
                          ),
                        ),
                        if (a.isUnlocked)
                          const Text(
                            'UNLOCKED',
                            style: TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.accentGreen, fontSize: 10, fontWeight: FontWeight.bold),
                          ),
                      ],
                    ),
                  ),
                )),
          ],
        ),
      ),
    );
  }
}
