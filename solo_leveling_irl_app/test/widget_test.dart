import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:provider/provider.dart';
import 'package:sqflite_common_ffi/sqflite_ffi.dart';
import 'package:solo_leveling_irl_app/main.dart';
import 'package:solo_leveling_irl_app/providers/system_state_provider.dart';

void main() {
  // Initialize FFI for tests
  sqfliteFfiInit();
  databaseFactory = databaseFactoryFfi;

  testWidgets('Splash Screen and gate evaluation smoke test', (WidgetTester tester) async {
    // Build our app and trigger a frame.
    await tester.pumpWidget(
      ChangeNotifierProvider(
        create: (context) => SystemStateProvider(),
        child: const SoloLevelingIrlApp(),
      ),
    );

    // Verify that splash elements are visible initially
    expect(find.text('SOLO LEVELING: IRL'), findsOneWidget);
    expect(find.text('OFFLINE AI LIFE OPERATING SYSTEM'), findsOneWidget);

    // Pump with a limit to avoid full pumpAndSettle infinite timers
    await tester.pump(const Duration(seconds: 4));
  });
}
