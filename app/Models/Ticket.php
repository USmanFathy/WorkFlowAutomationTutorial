<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'date'
    ];

    public static function columnDate():string{
        return 'date';
    }
    public static function userForeignKey():string{
        return 'user_id';
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
