<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Supporter;

/**
 * $table->integer('campaign_id');
 * $table->string('mail');
 * $table->dateTime('invitation_sent')->default(null)->nullable();
 */
class Invitation extends Model
{
    protected $fillable = ['campaign_id', 'mail'];
    use Notifiable;
    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->mail;
    }
}
