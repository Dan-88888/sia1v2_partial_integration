<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'reg_audit_logs';

    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id', 'payload', 'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
