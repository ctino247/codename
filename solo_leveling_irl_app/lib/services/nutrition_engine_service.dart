class NutritionEngineService {
  /// Calculates daily goals based on gender, age, height, weight, activity and goal type
  Map<String, int> calculateMacros({
    required String gender,
    required int age,
    required double height,
    required double weight,
    required String activityLevel,
    required String goal, // "Lose Weight", "Gain Muscle", "Maintain"
  }) {
    // 1. Calculate Basal Metabolic Rate (BMR) using Mifflin-St Jeor Equation
    double bmr;
    if (gender == 'Male') {
      bmr = (10 * weight) + (6.25 * height) - (5 * age) + 5;
    } else {
      bmr = (10 * weight) + (6.25 * height) - (5 * age) - 161;
    }

    // 2. Adjust for Activity Level
    double tdee = bmr * 1.2;
    switch (activityLevel) {
      case 'Light':
        tdee = bmr * 1.375;
        break;
      case 'Moderate':
        tdee = bmr * 1.55;
        break;
      case 'Active':
        tdee = bmr * 1.725;
        break;
      case 'Very Active':
        tdee = bmr * 1.9;
        break;
    }

    // 3. Adjust for weight goals
    double calorieTarget = tdee;
    if (goal == 'Lose Weight') {
      calorieTarget = tdee - 500;
    } else if (goal == 'Gain Muscle') {
      calorieTarget = tdee + 300;
    }

    // 4. Calculate protein target: 2g per kg of bodyweight for active weight loss / muscle gains
    double proteinTarget = weight * 2.0;

    // 5. Water intake: 35ml per kg of bodyweight
    double waterTarget = weight * 35;

    return {
      'calories': calorieTarget.round(),
      'protein': proteinTarget.round(),
      'water': waterTarget.round(),
    };
  }

  /// Suggest daily meals based on stored culinary preferences
  List<Map<String, String>> generateMealSuggestions(String preferences) {
    final lower = preferences.toLowerCase();
    if (lower.contains('veg') || lower.contains('plant')) {
      return [
        {
          'type': 'Breakfast',
          'name': 'Oatmeal with chia seeds, banana, and protein powder',
          'calories': '450',
          'protein': '25g'
        },
        {
          'type': 'Lunch',
          'name': 'Quinoa salad with roasted chickpeas, tofu, and olive oil',
          'calories': '650',
          'protein': '30g'
        },
        {
          'type': 'Dinner',
          'name': 'Lentil stew with brown rice and broccoli',
          'calories': '700',
          'protein': '35g'
        },
        {
          'type': 'Snack',
          'name': 'Mixed almonds and pumpkin seeds',
          'calories': '250',
          'protein': '10g'
        },
      ];
    }

    return [
      {
        'type': 'Breakfast',
        'name': 'Scrambled Eggs (3) with whole wheat toast and avocado',
        'calories': '500',
        'protein': '30g'
      },
      {
        'type': 'Lunch',
        'name': 'Grilled Chicken Breast with brown rice and asparagus',
        'calories': '650',
        'protein': '45g'
      },
      {
        'type': 'Dinner',
        'name': 'Baked Salmon Filet with sweet potato and steamed spinach',
        'calories': '700',
        'protein': '40g'
      },
      {
        'type': 'Snack',
        'name': 'Greek Yogurt (0%) with mixed berries and honey',
        'calories': '250',
        'protein': '22g'
      },
    ];
  }
}
