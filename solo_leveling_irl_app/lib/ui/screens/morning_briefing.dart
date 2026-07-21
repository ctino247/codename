import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/system_state_provider.dart';
import '../widgets/glass_widgets.dart';

class MorningBriefingScreen extends StatefulWidget {
  final VoidCallback onComplete;
  const MorningBriefingScreen({super.key, required this.onComplete});

  @override
  State<MorningBriefingScreen> createState() => _MorningBriefingScreenState();
}

class _MorningBriefingScreenState extends State<MorningBriefingScreen> {
  final TextEditingController _voiceInputController = TextEditingController();
  bool _isListening = false;
  String _systemFeedback = '';

  void _simulateVoiceRecording() async {
    setState(() {
      _isListening = true;
      _voiceInputController.text = "Initializing speech capture...";
    });

    await Future.delayed(const Duration(seconds: 2));

    // Sample phrase matching our custom task objectives
    const simulatedTranscription =
        "I need to finish my Flutter project, work for six hours, buy groceries, study for two hours, go to the gym at six, and sleep before ten.";

    setState(() {
      _isListening = false;
      _voiceInputController.text = simulatedTranscription;
    });
  }

  void _submitBriefing() async {
    final input = _voiceInputController.text.trim();
    if (input.isEmpty) return;

    final provider = Provider.of<SystemStateProvider>(context, listen: false);
    final response = await provider.processNlpInput(input);

    setState(() {
      _systemFeedback = response;
    });

    await Future.delayed(const Duration(seconds: 3));
    widget.onComplete();
  }

  @override
  Widget build(BuildContext context) {
    final user = Provider.of<SystemStateProvider>(context).userProfile;
    final hunterName = user?.name ?? "Hunter";

    return Scaffold(
      backgroundColor: ThemeColors.background,
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 10),
              Center(
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  decoration: BoxDecoration(
                    color: ThemeColors.accentPurple.withOpacity(0.1),
                    border: Border.all(color: ThemeColors.accentPurple),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: const Text(
                    'AI MORNING BRIEFING',
                    style: TextStyle(
                      color: ThemeColors.accentPurple,
                      fontFamily: 'JetBrains Mono',
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 30),
              Text(
                'Good morning, $hunterName.',
                style: const TextStyle(
                  fontFamily: 'Montserrat',
                  fontSize: 26,
                  fontWeight: FontWeight.w900,
                  color: Colors.white,
                ),
              ),
              const SizedBox(height: 10),
              const Text(
                'What are today\'s objective priorities? The system will compile a structured timeline for you.',
                style: TextStyle(color: ThemeColors.textSecondary, fontSize: 15),
              ),
              const SizedBox(height: 30),
              GlassCard(
                glowColor: _isListening ? ThemeColors.accentPurple : null,
                child: Column(
                  children: [
                    const Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          'SPEECH TRANSCRIPTION',
                          style: TextStyle(
                            color: ThemeColors.primaryBlue,
                            fontFamily: 'JetBrains Mono',
                            fontSize: 12,
                          ),
                        ),
                        Icon(Icons.record_voice_over, color: ThemeColors.primaryBlue, size: 18),
                      ],
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: _voiceInputController,
                      maxLines: 5,
                      style: const TextStyle(color: Colors.white, fontSize: 16),
                      decoration: InputDecoration(
                        hintText: "Speak or type details. Example:\n\"Finish Flutter coding, study 2h, hit gym at 6pm.\"",
                        hintStyle: TextStyle(color: Colors.white.withOpacity(0.3)),
                        border: InputBorder.none,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 40),
              Center(
                child: Column(
                  children: [
                    GestureDetector(
                      onTap: _simulateVoiceRecording,
                      child: Container(
                        width: 90,
                        height: 90,
                        decoration: BoxDecoration(
                          color: _isListening ? ThemeColors.accentPurple.withOpacity(0.2) : ThemeColors.primaryBlue.withOpacity(0.1),
                          shape: BoxShape.circle,
                          border: Border.all(
                            color: _isListening ? ThemeColors.accentPurple : ThemeColors.primaryBlue,
                            width: 3,
                          ),
                          boxShadow: [
                            BoxShadow(
                              color: (_isListening ? ThemeColors.accentPurple : ThemeColors.primaryBlue).withOpacity(0.3),
                              blurRadius: 20,
                              spreadRadius: 2,
                            )
                          ],
                        ),
                        child: Icon(
                          _isListening ? Icons.graphic_eq : Icons.mic,
                          color: _isListening ? ThemeColors.accentPurple : ThemeColors.primaryBlue,
                          size: 40,
                        ),
                      ),
                    ),
                    const SizedBox(height: 15),
                    Text(
                      _isListening ? 'CAPTURING VOCAL FREQUENCIES...' : 'TAP MICROPHONE TO SIMULATE SPEECH INPUT',
                      style: TextStyle(
                        fontFamily: 'JetBrains Mono',
                        fontSize: 12,
                        color: _isListening ? ThemeColors.accentPurple : ThemeColors.textSecondary,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 40),
              if (_systemFeedback.isNotEmpty)
                GlassCard(
                  glowColor: ThemeColors.accentGreen,
                  child: Text(
                    _systemFeedback,
                    style: const TextStyle(color: Colors.white, fontSize: 14),
                  ),
                ),
              const SizedBox(height: 20),
              Center(
                child: ElevatedButton(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: ThemeColors.primaryBlue,
                    padding: const EdgeInsets.symmetric(horizontal: 40, vertical: 16),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  onPressed: _submitBriefing,
                  child: const Text(
                    'COMPILE DUNGEON PROTOCOLS',
                    style: TextStyle(
                      fontFamily: 'Montserrat',
                      fontWeight: FontWeight.w900,
                      color: Color(0xFF00363a),
                      fontSize: 14,
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 20),
              Center(
                child: TextButton(
                  onPressed: widget.onComplete,
                  child: const Text(
                    'SKIP TO DASHBOARD',
                    style: TextStyle(color: ThemeColors.textSecondary, fontFamily: 'JetBrains Mono'),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
