<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CampaignInvited extends Notification
{
    use Queueable;

    public $campaign;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * use Route::get( '/foerdern/{campaign_uuid}/{invitation}',  'SupporterController@index');
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->error()
                    ->from('info@transition-regensburg.de', 'Alternatives Wohnen - Transition Regensburg')
                    ->subject('Einladung zur Teilname an '. $this->campaign->name)
                    ->greeting('Hallo!')
                    ->line('Dies ist deine persönliche Einladung zur Teilnahme am Projekt ' . $this->campaign->name)
                    ->line('')
                    ->line($this->campaign->description)
                    ->action('Jetzt mitmachen', action(
                      'SupporterController@support', [
                        'campaign_uuid' => $this->campaign->uuid,
                        'invitation_uuid' => $notifiable->uuid
                      ]))
                    ->line('Der Link bleibt weiterhin gültig, so kannst du deine Daten später selber aktualisieren, aus dem Grund sollte diese Mail auch nicht weitergegeben werden.')
                    ->line('Schön dass du dabei bist!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
