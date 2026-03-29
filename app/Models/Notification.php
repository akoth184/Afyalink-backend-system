<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Notification extends Model
{
    protected $table = 'afya_notifications';
    protected $fillable = [
        'user_id','type','title','message','referral_id','is_read'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }
    public static function send($userId, $type, $title, $message, $referralId = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'referral_id' => $referralId,
            'is_read' => false,
        ]);
    }
}
