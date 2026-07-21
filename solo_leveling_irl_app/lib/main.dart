import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/system_state_provider.dart';
import 'ui/screens/onboarding_initiation.dart';
import 'ui/screens/hunter_dashboard.dart';
import 'ui/widgets/glass_widgets.dart';

void main() {
  runApp(
    ChangeNotifierProvider(
      create: (context) => SystemStateProvider(),
      child: const SoloLevelingIrlApp(),
    ),
  );
}

class SoloLevelingIrlApp extends StatelessWidget {
  const SoloLevelingIrlApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Solo Leveling: IRL',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        brightness: Brightness.dark,
        scaffoldBackgroundColor: ThemeColors.background,
        primaryColor: ThemeColors.primaryBlue,
        fontFamily: 'Inter',
        useMaterial3: true,
      ),
      home: const ApplicationLifecycleGate(),
    );
  }
}

class ApplicationLifecycleGate extends StatefulWidget {
  const ApplicationLifecycleGate({super.key});

  @override
  State<ApplicationLifecycleGate> createState() => _ApplicationLifecycleGateState();
}

class _ApplicationLifecycleGateState extends State<ApplicationLifecycleGate> {
  bool _showSplash = true;

  @override
  void initState() {
    super.initState();
    _dismissSplash();
  }

  void _dismissSplash() async {
    await Future.delayed(const Duration(seconds: 3));
    if (mounted) {
      setState(() {
        _showSplash = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<SystemStateProvider>(context);

    if (_showSplash || provider.isLoading) {
      return const HolographicSplashScreen();
    }

    // Direct to onboarding if user name represents the unconfigured default state
    final user = provider.userProfile;
    if (user == null || user.age == 24 && user.name == 'Jin-Woo' && user.xp == 0 && user.streak == 0) {
      return OnboardingInitiation(
        onComplete: () {
          setState(() {});
        },
      );
    }

    return const HunterDashboardScreen();
  }
}

class HolographicSplashScreen extends StatelessWidget {
  const HolographicSplashScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ThemeColors.background,
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                border: Border.all(color: ThemeColors.primaryBlue, width: 2),
                boxShadow: [
                  BoxShadow(
                    color: ThemeColors.primaryBlue.withOpacity(0.2),
                    blurRadius: 40,
                    spreadRadius: 5,
                  )
                ],
              ),
              child: const Icon(Icons.flash_on, color: ThemeColors.primaryBlue, size: 64),
            ),
            const SizedBox(height: 32),
            const Text(
              'SOLO LEVELING: IRL',
              style: TextStyle(
                fontFamily: 'Montserrat',
                fontSize: 28,
                fontWeight: FontWeight.w900,
                letterSpacing: 2.0,
                color: Colors.white,
              ),
            ),
            const SizedBox(height: 8),
            const Text(
              'OFFLINE AI LIFE OPERATING SYSTEM',
              style: TextStyle(
                fontFamily: 'JetBrains Mono',
                fontSize: 12,
                letterSpacing: 1.5,
                color: ThemeColors.textSecondary,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 48),
            const CircularProgressIndicator(color: ThemeColors.primaryBlue),
          ],
        ),
      ),
    );
  }
}
