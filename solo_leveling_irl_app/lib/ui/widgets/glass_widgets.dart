import 'package:flutter/material.dart';

class ThemeColors {
  static const Color background = Color(0xFF0B0B0F); // Obsidian
  static const Color primaryBlue = Color(0xFF00F2FF); // Electric Blue / Mana
  static const Color primaryBlueDim = Color(0xFF00A2AA);
  static const Color accentGreen = Color(0xFF39FF14); // Neon Green / HP
  static const Color accentPurple = Color(0xFFBC13FE); // S-Rank Purple
  static const Color textPrimary = Color(0xFFE4E1E7); // White 90%
  static const Color textSecondary = Color(0xFFB9CACB);

  static const Color glassSurface = Color(0x0FFFFFFF); // 3% White
  static const Color glassBorder = Color(0x1EFFFFFF); // 12% White
}

class GlassCard extends StatelessWidget {
  final Widget child;
  final double? width;
  final double? height;
  final EdgeInsetsGeometry? padding;
  final Color? glowColor;
  final double glowRadius;
  final VoidCallback? onTap;

  const GlassCard({
    super.key,
    required this.child,
    this.width,
    this.height,
    this.padding,
    this.glowColor,
    this.glowRadius = 15.0,
    this.onTap,
  });

  @override
  Widget build(BuildContext voidContext) {
    Widget card = Container(
      width: width,
      height: height,
      padding: padding ?? const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: ThemeColors.glassSurface,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: ThemeColors.glassBorder, width: 1),
        boxShadow: glowColor != null
            ? [
                BoxShadow(
                  color: glowColor!.withOpacity(0.35),
                  blurRadius: glowRadius,
                  spreadRadius: 2,
                )
              ]
            : null,
      ),
      child: child,
    );

    if (onTap != null) {
      return GestureDetector(
        onTap: onTap,
        child: card,
      );
    }
    return card;
  }
}

class SegmentedProgressDecoder extends StatelessWidget {
  final double percent; // 0.0 to 1.0
  final Color activeColor;
  final int totalSegments;
  final double height;

  const SegmentedProgressDecoder({
    super.key,
    required this.percent,
    required this.activeColor,
    this.totalSegments = 10,
    this.height = 10,
  });

  @override
  Widget build(BuildContext context) {
    return LayoutBuilder(
      builder: (context, constraints) {
        final double itemWidth = (constraints.maxWidth - ((totalSegments - 1) * 3)) / totalSegments;
        final int activeCount = (percent * totalSegments).round();

        return SizedBox(
          height: height,
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            itemCount: totalSegments,
            physics: const NeverScrollableScrollPhysics(),
            itemBuilder: (context, index) {
              final bool isActive = index < activeCount;
              return Container(
                width: itemWidth,
                margin: EdgeInsets.only(right: index == totalSegments - 1 ? 0 : 3),
                decoration: BoxDecoration(
                  color: isActive ? activeColor : activeColor.withOpacity(0.15),
                  borderRadius: BorderRadius.circular(2),
                  boxShadow: isActive
                      ? [
                          BoxShadow(
                            color: activeColor.withOpacity(0.3),
                            blurRadius: 3,
                          )
                        ]
                      : null,
                ),
              );
            },
          ),
        );
      },
    );
  }
}
