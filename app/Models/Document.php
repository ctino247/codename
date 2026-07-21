<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'file_path',
        'status',
        'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function typeLabel(string $type): string
    {
        return match ($type) {
            'national_id' => 'National ID / Driver\'s License',
            'passport_photo' => 'Passport Photograph',
            'proof_of_address' => 'Proof of Address',
            default => ucwords(str_replace('_', ' ', $type)),
        };
    }
}
