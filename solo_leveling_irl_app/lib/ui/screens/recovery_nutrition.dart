import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/system_state_provider.dart';
import '../widgets/glass_widgets.dart';

class RecoveryNutritionScreen extends StatefulWidget {
  const RecoveryNutritionScreen({super.key});

  @override
  State<RecoveryNutritionScreen> createState() => _RecoveryNutritionScreenState();
}

class _RecoveryNutritionScreenState extends State<RecoveryNutritionScreen> {
  final _foodNameController = TextEditingController();
  final _caloriesController = TextEditingController(text: '350');
  final _proteinController = TextEditingController(text: '20');
  String _mealType = 'Breakfast';

  void _showAddMealModal() {
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
                    'LOG NUTRITIONAL FUELS',
                    style: TextStyle(
                      fontFamily: 'Montserrat',
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 20),
                  DropdownButtonFormField<String>(
                    value: _mealType,
                    dropdownColor: ThemeColors.background,
                    style: const TextStyle(color: Colors.white),
                    decoration: const InputDecoration(labelText: 'Meal Categorization', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                    items: ['Breakfast', 'Lunch', 'Dinner', 'Snack']
                        .map((m) => DropdownMenuItem(value: m, child: Text(m)))
                        .toList(),
                    onChanged: (val) {
                      if (val != null) setState(() => _mealType = val);
                    },
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _foodNameController,
                    style: const TextStyle(color: Colors.white),
                    decoration: const InputDecoration(
                      labelText: 'Nutritional Item (e.g. Eggs and avocado toast)',
                      labelStyle: TextStyle(color: ThemeColors.textSecondary),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                        child: TextField(
                          controller: _caloriesController,
                          keyboardType: TextInputType.number,
                          style: const TextStyle(color: Colors.white),
                          decoration: const InputDecoration(labelText: 'Calorie Count (kcal)', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: TextField(
                          controller: _proteinController,
                          keyboardType: TextInputType.number,
                          style: const TextStyle(color: Colors.white),
                          decoration: const InputDecoration(labelText: 'Protein Density (g)', labelStyle: TextStyle(color: ThemeColors.textSecondary)),
                        ),
                      ),
                    ],
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
                          final food = _foodNameController.text.trim();
                          final calories = int.tryParse(_caloriesController.text) ?? 300;
                          final protein = int.tryParse(_proteinController.text) ?? 20;
                          if (food.isNotEmpty) {
                            final provider = Provider.of<SystemStateProvider>(context, listen: false);
                            await provider.addMeal(_mealType, food, calories, protein);
                            _foodNameController.clear();
                            if (mounted) Navigator.pop(context);
                          }
                        },
                        child: const Text('INGEST FUEL', style: TextStyle(color: Color(0xFF00363a), fontWeight: FontWeight.bold)),
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
    final profile = provider.userProfile;
    final meals = provider.todayMeals;

    final targetCalories = profile?.calorieGoal ?? 2200;
    final targetProtein = profile?.proteinGoal ?? 150;
    final targetWater = profile?.waterGoal ?? 3000;

    int totalCalories = 0;
    int totalProtein = 0;
    for (var m in meals) {
      totalCalories += m.calories;
      totalProtein += m.protein;
    }

    int totalWaterAmount = 0;
    for (var w in provider.todayWater) {
      totalWaterAmount += w.amountMl;
    }

    final double caloriePercent = (totalCalories / targetCalories).clamp(0.0, 1.0);
    final double proteinPercent = (totalProtein / targetProtein).clamp(0.0, 1.0);
    final double waterPercent = (totalWaterAmount / targetWater).clamp(0.0, 1.0);

    // Active Suggestions from stored preferences
    final suggestions = provider.nutritionEngineService.generateMealSuggestions(profile?.foodPreferences ?? 'beef');

    return Scaffold(
      backgroundColor: ThemeColors.background,
      appBar: AppBar(
        title: const Text('RECOVERY & NUTRITION', style: TextStyle(fontFamily: 'Montserrat', fontWeight: FontWeight.bold)),
        backgroundColor: Colors.transparent,
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Status Grid
            Row(
              children: [
                Expanded(
                  child: GlassCard(
                    child: Column(
                      children: [
                        const Text('CALORIES', style: TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.textSecondary, fontSize: 12, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 8),
                        Text('$totalCalories / $targetCalories', style: const TextStyle(fontFamily: 'JetBrains Mono', fontSize: 18, color: Colors.white, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 8),
                        SegmentedProgressDecoder(percent: caloriePercent, activeColor: ThemeColors.primaryBlue),
                      ],
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: GlassCard(
                    child: Column(
                      children: [
                        const Text('PROTEIN', style: TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.textSecondary, fontSize: 12, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 8),
                        Text('${totalProtein}g / ${targetProtein}g', style: const TextStyle(fontFamily: 'JetBrains Mono', fontSize: 18, color: Colors.white, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 8),
                        SegmentedProgressDecoder(percent: proteinPercent, activeColor: ThemeColors.accentPurple),
                      ],
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            GlassCard(
              child: Column(
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('WATER STAMINA RECOVERY', style: TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.textSecondary, fontSize: 12, fontWeight: FontWeight.bold)),
                      Text('$totalWaterAmount / $targetWater ml', style: const TextStyle(fontFamily: 'JetBrains Mono', color: Colors.white, fontSize: 14, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  const SizedBox(height: 12),
                  SegmentedProgressDecoder(percent: waterPercent, activeColor: ThemeColors.primaryBlue),
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                    children: [
                      ElevatedButton(
                        style: ElevatedButton.styleFrom(backgroundColor: ThemeColors.primaryBlue.withOpacity(0.2)),
                        onPressed: () => provider.logWaterIntake(250),
                        child: const Text('+250ml', style: TextStyle(color: ThemeColors.primaryBlue)),
                      ),
                      ElevatedButton(
                        style: ElevatedButton.styleFrom(backgroundColor: ThemeColors.primaryBlue.withOpacity(0.2)),
                        onPressed: () => provider.logWaterIntake(500),
                        child: const Text('+500ml', style: TextStyle(color: ThemeColors.primaryBlue)),
                      ),
                      ElevatedButton(
                        style: ElevatedButton.styleFrom(backgroundColor: ThemeColors.primaryBlue.withOpacity(0.2)),
                        onPressed: () => provider.logWaterIntake(750),
                        child: const Text('+750ml', style: TextStyle(color: ThemeColors.primaryBlue)),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  'LOGGED INGESTED FUELS',
                  style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
                ),
                ElevatedButton.icon(
                  style: ElevatedButton.styleFrom(backgroundColor: ThemeColors.primaryBlue),
                  onPressed: _showAddMealModal,
                  icon: const Icon(Icons.restaurant, color: Color(0xFF00363a), size: 16),
                  label: const Text('FUEL', style: TextStyle(color: Color(0xFF00363a), fontWeight: FontWeight.bold)),
                ),
              ],
            ),
            const SizedBox(height: 12),
            if (meals.isEmpty)
              const GlassCard(
                child: Center(
                  child: Text('No recovery fuel ingested today. Log some items.', style: TextStyle(color: ThemeColors.textSecondary)),
                ),
              )
            else
              ...meals.map((m) => Container(
                    margin: const EdgeInsets.only(bottom: 8),
                    child: GlassCard(
                      child: Row(
                        children: [
                          Icon(Icons.restaurant_menu, color: ThemeColors.accentPurple, size: 20),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(m.foodName, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14)),
                                Text('${m.mealType} • ${m.calories} kcal • ${m.protein}g protein', style: const TextStyle(color: ThemeColors.textSecondary, fontSize: 12)),
                              ],
                            ),
                          ),
                          IconButton(
                            icon: const Icon(Icons.delete_outline, color: Colors.redAccent, size: 18),
                            onPressed: () => provider.deleteMealLog(m.id!),
                          ),
                        ],
                      ),
                    ),
                  )),
            const SizedBox(height: 24),
            const Text(
              'DUNGEON MEAL RECOMMENDATIONS',
              style: TextStyle(fontFamily: 'Montserrat', fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            const SizedBox(height: 12),
            ...suggestions.map((s) => Container(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: GlassCard(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(s['type']!.toUpperCase(), style: const TextStyle(fontFamily: 'JetBrains Mono', color: ThemeColors.primaryBlue, fontSize: 11, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 4),
                        Text(s['name']!, style: const TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 4),
                        Text('Estimate Cal: ${s['calories']} kcal • Protein: ${s['protein']}', style: const TextStyle(color: ThemeColors.textSecondary, fontSize: 12)),
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
