import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/system_state_provider.dart';
import '../widgets/glass_widgets.dart';
import 'morning_briefing.dart';
import 'daily_schedule_timeline.dart';
import 'quest_board.dart';
import 'recovery_nutrition.dart';
import 'training_center.dart';
import 'hunter_journal.dart';
import 'system_analytics.dart';
import 'system_settings.dart';

class HunterDashboardScreen extends StatefulWidget {
  const HunterDashboardScreen({super.key});

  @override
  State<HunterDashboardScreen> createState() => _HunterDashboardScreenState();
}

class _HunterDashboardScreenState extends State<HunterDashboardScreen> {
  int _currentIndex = 0;

  final List<Widget> _pages = [
    const HomeDashboardContent(),
    const DailyScheduleTimelineScreen(),
    const QuestBoardScreen(),
    const RecoveryNutritionScreen(),
    const TrainingCenterScreen(),
    const HunterJournalScreen(),
    const SystemAnalyticsScreen(),
    const SystemSettingsScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ThemeColors.background,
      body: _pages[_currentIndex],
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex >= 4 ? 4 : _currentIndex, // group extra pages or navigate from side
        backgroundColor: ThemeColors.background,
        selectedItemColor: ThemeColors.primaryBlue,
        unselectedItemColor: ThemeColors.textSecondary,
        type: BottomNavigationBarType.fixed,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.dashboard_outlined), label: 'Dashboard'),
          BottomNavigationBarItem(icon: Icon(Icons.calendar_month_outlined), label: 'Timeline'),
          BottomNavigationBarItem(icon: Icon(Icons.check_circle_outline), label: 'Quests'),
          BottomNavigationBarItem(icon: Icon(Icons.restaurant_outlined), label: 'Nutrition'),
          BottomNavigationBarItem(icon: Icon(Icons.fitness_center), label: 'Training'),
        ],
        onTap: (index) {
          setState(() {
            _currentIndex = index;
          });
        },
      ),
      drawer: Drawer(
        backgroundColor: ThemeColors.background,
        child: ListView(
          padding: EdgeInsets.zero,
          children: [
            DrawerHeader(
              decoration: const BoxDecoration(
                color: ThemeColors.glassSurface,
                border: Border(bottom: BorderSide(color: ThemeColors.glassBorder)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.end,
                children: [
                  const Text(
                    'SOLO LEVELING: IRL',
                    style: TextStyle(fontFamily: 'Montserrat', fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'Offline Life operating system',
                    style: TextStyle(color: ThemeColors.textSecondary, fontSize: 13),
                  ),
                ],
              ),
            ),
            _buildDrawerItem(0, 'System Dashboard', Icons.dashboard_outlined),
            _buildDrawerItem(1, 'Daily Schedule Timeline', Icons.calendar_month_outlined),
            _buildDrawerItem(2, 'Quest Board Sector', Icons.check_circle_outline),
            _buildDrawerItem(3, 'Recovery & Nutrition', Icons.restaurant_outlined),
            _buildDrawerItem(4, 'Training Center', Icons.fitness_center),
            _buildDrawerItem(5, 'Hunter\'s Journal / Night review', Icons.book_outlined),
            _buildDrawerItem(6, 'System Analytics Graphs', Icons.auto_graph_outlined),
            _buildDrawerItem(7, 'System Settings', Icons.settings_outlined),
          ],
        ),
      ),
      appBar: AppBar(
        title: const Text('SOLO LEVELING: IRL', style: TextStyle(fontFamily: 'Montserrat', fontWeight: FontWeight.w900, fontSize: 16)),
        backgroundColor: Colors.transparent,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.white),
      ),
    );
  }

  Widget _buildDrawerItem(int index, String title, IconData icon) {
    return ListTile(
      leading: Icon(icon, color: _currentIndex == index ? ThemeColors.primaryBlue : ThemeColors.textSecondary),
      title: Text(title, style: TextStyle(color: _currentIndex == index ? ThemeColors.primaryBlue : Colors.white)),
      onTap: () {
        setState(() {
          _currentIndex = index;
        });
        Navigator.pop(context);
      },
    );
  }
}

class HomeDashboardContent extends StatelessWidget {
  const HomeDashboardContent({super.key});

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<SystemStateProvider>(context);
    final user = provider.userProfile;

    if (user == null) {
      return const Center(child: CircularProgressIndicator(color: ThemeColors.primaryBlue));
    }

    final int targetCal = user.calorieGoal ?? 2200;
    final int targetWater = user.waterGoal ?? 3000;

    int currentCal = 0;
    for (var m in provider.todayMeals) {
      currentCal += m.calories;
    }

    int currentWater = 0;
    for (var w in provider.todayWater) {
      currentWater += w.amountMl;
    }

    final double xpPercent = (user.xp / (user.level * 100)).clamp(0.0, 1.0);

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Holographic Rank Indicator Card
          GlassCard(
            glowColor: ThemeColors.accentPurple,
            child: Row(
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(
                          color: ThemeColors.accentPurple.withOpacity(0.1),
                          border: Border.all(color: ThemeColors.accentPurple, width: 0.5),
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Text(
                          'RANK: ${user.rank.toUpperCase()}',
                          style: const TextStyle(
                            fontFamily: 'JetBrains Mono',
                            fontSize: 12,
                            fontWeight: FontWeight.bold,
                            color: ThemeColors.accentPurple,
                          ),
                        ),
                      ),
                      const SizedBox(height: 12),
                      Text(
                        user.name.toUpperCase(),
                        style: const TextStyle(
                          fontFamily: 'Montserrat',
                          fontSize: 24,
                          fontWeight: FontWeight.w900,
                          color: Colors.white,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        'Day ${user.dayCount} of 100 Challenge',
                        style: const TextStyle(color: ThemeColors.textSecondary, fontSize: 13),
                      ),
                    ],
                  ),
                ),
                Column(
                  children: [
                    const Text('LEVEL', style: TextStyle(fontFamily: 'JetBrains Mono', fontSize: 12, color: ThemeColors.textSecondary)),
                    Text(
                      '${user.level}',
                      style: const TextStyle(
                        fontFamily: 'Montserrat',
                        fontSize: 48,
                        fontWeight: FontWeight.w900,
                        color: ThemeColors.primaryBlue,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),

          // Experience Bar
          GlassCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('EXPERIENCE POINT (XP)', style: TextStyle(fontFamily: 'JetBrains Mono', fontSize: 11, color: ThemeColors.textSecondary, fontWeight: FontWeight.bold)),
                    Text('${user.xp} / ${user.level * 100} XP', style: const TextStyle(fontFamily: 'JetBrains Mono', fontSize: 12, color: Colors.white, fontWeight: FontWeight.bold)),
                  ],
                ),
                const SizedBox(height: 10),
                SegmentedProgressDecoder(percent: xpPercent, activeColor: ThemeColors.primaryBlue),
              ],
            ),
          ),
          const SizedBox(height: 16),

          // Core Biometrics HUD Grid
          GridView.count(
            crossAxisCount: 2,
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            crossAxisSpacing: 12,
            mainAxisSpacing: 12,
            childAspectRatio: 1.4,
            children: [
              _buildHudCard('CALORIES', '$currentCal / $targetCal', 'kcal', Icons.restaurant, ThemeColors.primaryBlue),
              _buildHudCard('WATER STAMINA', '$currentWater / $targetWater', 'ml', Icons.local_drink, ThemeColors.primaryBlue),
              _buildHudCard('SLEEP DUR.', provider.sleepHistory.isNotEmpty ? '${provider.sleepHistory.first.durationHours}h' : '8h', 'Sleep target', Icons.bed, ThemeColors.accentPurple),
              _buildHudCard('STREAK', '${user.streak} Days', 'Continuous Clear', Icons.workspace_premium, ThemeColors.accentGreen),
            ],
          ),
          const SizedBox(height: 24),

          // Daily Morning Prompt Launcher
          const Text(
            'ACTIVE DAILY MISSIONS & DUNGEONS',
            style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
          ),
          const SizedBox(height: 12),
          GestureDetector(
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => MorningBriefingScreen(onComplete: () {
                    Navigator.pop(context);
                  }),
                ),
              );
            },
            child: const GlassCard(
              glowColor: ThemeColors.accentGreen,
              child: Row(
                children: [
                  Icon(Icons.mic, color: ThemeColors.accentGreen, size: 28),
                  SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('LAUNCH MORNING BRIEFING', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 15)),
                        SizedBox(height: 4),
                        Text('Instruct the system using voice NLP to compile schedules.', style: TextStyle(color: ThemeColors.textSecondary, fontSize: 12)),
                      ],
                    ),
                  ),
                  Icon(Icons.arrow_forward_ios, color: ThemeColors.textSecondary, size: 16),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Timeline overview widget
          const Text(
            'TODAY\'S CHRONOLOGICAL TIMELINE',
            style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
          ),
          const SizedBox(height: 12),
          if (provider.todayTasks.isEmpty)
            const GlassCard(
              child: Center(
                child: Text('No chronological timeline compiled. Tap Morning Briefing to setup.', style: TextStyle(color: ThemeColors.textSecondary)),
              ),
            )
          else
            ListView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: provider.todayTasks.length > 3 ? 3 : provider.todayTasks.length,
              itemBuilder: (context, index) {
                final t = provider.todayTasks[index];
                return Container(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: GlassCard(
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(t.title, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                        Text(t.scheduledTime ?? 'TBD', style: const TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.primaryBlue)),
                      ],
                    ),
                  ),
                );
              },
            ),
        ],
      ),
    );
  }

  Widget _buildHudCard(String label, String value, String unit, IconData icon, Color accentColor) {
    return GlassCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(label, style: const TextStyle(fontFamily: 'JetBrains Mono', fontSize: 11, color: ThemeColors.textSecondary, fontWeight: FontWeight.bold)),
              Icon(icon, color: accentColor, size: 16),
            ],
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(value, style: const TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold)),
              Text(unit, style: const TextStyle(color: ThemeColors.textSecondary, fontSize: 11)),
            ],
          ),
        ],
      ),
    );
  }
}
