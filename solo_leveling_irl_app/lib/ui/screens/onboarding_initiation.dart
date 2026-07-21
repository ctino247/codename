import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/system_state_provider.dart';
import '../../models/task_model.dart';
import '../widgets/glass_widgets.dart';

class OnboardingInitiation extends StatefulWidget {
  final VoidCallback onComplete;
  const OnboardingInitiation({super.key, required this.onComplete});

  @override
  State<OnboardingInitiation> createState() => _OnboardingInitiationState();
}

class _OnboardingInitiationState extends State<OnboardingInitiation> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController(text: 'Jin-Woo');
  int _age = 24;
  double _height = 178.0;
  double _weight = 80.0;
  double _targetWeight = 72.0;
  String _gender = 'Male';
  String _activityLevel = 'Moderate';
  String _workoutPreference = 'Gym Workouts';
  String _foodPreferences = 'High protein, beef, eggs, brown rice';
  String _goal = 'Lose Weight';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ThemeColors.background,
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24.0),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const SizedBox(height: 20),
                Center(
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    decoration: BoxDecoration(
                      border: Border.all(color: ThemeColors.primaryBlue),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: const Text(
                      'SYSTEM INITIATION',
                      style: TextStyle(
                        color: ThemeColors.primaryBlue,
                        fontFamily: 'JetBrains Mono',
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        letterSpacing: 0.15,
                      ),
                    ),
                  ),
                ),
                const SizedBox(height: 20),
                const Center(
                  child: Text(
                    'ONBOARDING SYSTEM',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      fontFamily: 'Montserrat',
                      fontSize: 28,
                      fontWeight: FontWeight.w900,
                      color: Colors.white,
                    ),
                  ),
                ),
                const SizedBox(height: 10),
                const Center(
                  child: Text(
                    'An executive evaluation to formulate your 100-day life-altering program.',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: ThemeColors.textSecondary,
                      fontSize: 14,
                    ),
                  ),
                ),
                const SizedBox(height: 30),
                GlassCard(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'HUNTER PROFILE EVALUATION',
                        style: TextStyle(
                          color: ThemeColors.primaryBlue,
                          fontFamily: 'JetBrains Mono',
                          fontSize: 14,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 20),
                      TextFormField(
                        controller: _nameController,
                        style: const TextStyle(color: Colors.white),
                        decoration: const InputDecoration(
                          labelText: 'Hunter Codename (Name)',
                          labelStyle: TextStyle(color: ThemeColors.textSecondary),
                          enabledBorder: UnderlineInputBorder(borderSide: BorderSide(color: ThemeColors.glassBorder)),
                          focusedBorder: UnderlineInputBorder(borderSide: BorderSide(color: ThemeColors.primaryBlue)),
                        ),
                        validator: (value) => value == null || value.isEmpty ? 'Coder name is mandatory' : null,
                      ),
                      const SizedBox(height: 20),
                      Row(
                        children: [
                          Expanded(
                            child: DropdownButtonFormField<String>(
                              value: _gender,
                              dropdownColor: ThemeColors.background,
                              style: const TextStyle(color: Colors.white),
                              decoration: const InputDecoration(labelText: 'Gender', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                              items: ['Male', 'Female', 'Non-Binary']
                                  .map((g) => DropdownMenuItem(value: g, child: Text(g)))
                                  .toList(),
                              onChanged: (val) {
                                if (val != null) setState(() => _gender = val);
                              },
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: TextFormField(
                              initialValue: _age.toString(),
                              keyboardType: TextInputType.number,
                              style: const TextStyle(color: Colors.white),
                              decoration: const InputDecoration(labelText: 'Age', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                              onChanged: (val) => _age = int.tryParse(val) ?? _age,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 20),
                      Row(
                        children: [
                          Expanded(
                            child: TextFormField(
                              initialValue: _height.toString(),
                              keyboardType: TextInputType.number,
                              style: const TextStyle(color: Colors.white),
                              decoration: const InputDecoration(labelText: 'Height (cm)', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                              onChanged: (val) => _height = double.tryParse(val) ?? _height,
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: TextFormField(
                              initialValue: _weight.toString(),
                              keyboardType: TextInputType.number,
                              style: const TextStyle(color: Colors.white),
                              decoration: const InputDecoration(labelText: 'Current Weight (kg)', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                              onChanged: (val) => _weight = double.tryParse(val) ?? _weight,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 20),
                      Row(
                        children: [
                          Expanded(
                            child: TextFormField(
                              initialValue: _targetWeight.toString(),
                              keyboardType: TextInputType.number,
                              style: const TextStyle(color: Colors.white),
                              decoration: const InputDecoration(labelText: 'Target Weight (kg)', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                              onChanged: (val) => _targetWeight = double.tryParse(val) ?? _targetWeight,
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: DropdownButtonFormField<String>(
                              value: _goal,
                              dropdownColor: ThemeColors.background,
                              style: const TextStyle(color: Colors.white),
                              decoration: const InputDecoration(labelText: 'Weight Goal', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                              items: ['Lose Weight', 'Gain Muscle', 'Maintain']
                                  .map((g) => DropdownMenuItem(value: g, child: Text(g)))
                                  .toList(),
                              onChanged: (val) {
                                if (val != null) setState(() => _goal = val);
                              },
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 16),
                GlassCard(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'PREFERENCE DECODING',
                        style: TextStyle(
                          color: ThemeColors.primaryBlue,
                          fontFamily: 'JetBrains Mono',
                          fontSize: 14,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 20),
                      DropdownButtonFormField<String>(
                        value: _activityLevel,
                        dropdownColor: ThemeColors.background,
                        style: const TextStyle(color: Colors.white),
                        decoration: const InputDecoration(labelText: 'Activity Tier', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                        items: ['Sedentary', 'Light', 'Moderate', 'Active', 'Very Active']
                            .map((g) => DropdownMenuItem(value: g, child: Text(g)))
                            .toList(),
                        onChanged: (val) {
                          if (val != null) setState(() => _activityLevel = val);
                        },
                      ),
                      const SizedBox(height: 20),
                      DropdownButtonFormField<String>(
                        value: _workoutPreference,
                        dropdownColor: ThemeColors.background,
                        style: const TextStyle(color: Colors.white),
                        decoration: const InputDecoration(labelText: 'Combat preference', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                        items: ['Home Workouts', 'Gym Workouts', 'Running / Cardio', 'Strength Training']
                            .map((g) => DropdownMenuItem(value: g, child: Text(g)))
                            .toList(),
                        onChanged: (val) {
                          if (val != null) setState(() => _workoutPreference = val);
                        },
                      ),
                      const SizedBox(height: 20),
                      TextFormField(
                        initialValue: _foodPreferences,
                        style: const TextStyle(color: Colors.white),
                        decoration: const InputDecoration(
                          labelText: 'Food/Dietary Preferences',
                          labelStyle: TextStyle(color: ThemeColors.textSecondary),
                        ),
                        onChanged: (val) => _foodPreferences = val,
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 32),
                Center(
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: ThemeColors.primaryBlue,
                      padding: const EdgeInsets.symmetric(horizontal: 50, vertical: 16),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    onPressed: () async {
                      if (_formKey.currentState!.validate()) {
                        await Provider.of<SystemStateProvider>(context, listen: false).saveOnboardingSetup(
                          name: _nameController.text,
                          age: _age,
                          height: _height,
                          weight: _weight,
                          targetWeight: _targetWeight,
                          gender: _gender,
                          activityLevel: _activityLevel,
                          workoutPreference: _workoutPreference,
                          foodPreferences: _foodPreferences,
                          goal: _goal,
                        );
                        widget.onComplete();
                      }
                    },
                    child: const Text(
                      'CONCLUDE INITIATION',
                      style: TextStyle(
                        fontFamily: 'Montserrat',
                        fontWeight: FontWeight.w900,
                        color: Color(0xFF00363a),
                        fontSize: 16,
                      ),
                    ),
                  ),
                ),
                const SizedBox(height: 30),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
