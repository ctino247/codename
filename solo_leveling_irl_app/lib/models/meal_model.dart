class MealModel {
  final int? id;
  final String date;
  final String mealType; // Breakfast, Lunch, Dinner, Snack
  final String foodName;
  final int calories;
  final int protein;

  MealModel({
    this.id,
    required this.date,
    required this.mealType,
    required this.foodName,
    required this.calories,
    required this.protein,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'date': date,
      'meal_type': mealType,
      'food_name': foodName,
      'calories': calories,
      'protein': protein,
    };
  }

  factory MealModel.fromMap(Map<String, dynamic> map) {
    return MealModel(
      id: map['id'],
      date: map['date'],
      mealType: map['meal_type'] ?? 'Snack',
      foodName: map['food_name'] ?? '',
      calories: map['calories'] ?? 0,
      protein: map['protein'] ?? 0,
    );
  }
}
