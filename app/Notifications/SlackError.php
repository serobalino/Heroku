<?php

namespace App\Notifications;

use App\Commits;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SlackError extends Notification
{
    use Queueable;

    protected $nuevo;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Commits $nuevo)
    {
        $this->nuevo=$nuevo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $github =   $this->nuevo->github;
        $url    =   url(route("generar", ["app" => $this->nuevo->app_co, "commit" => $this->nuevo->id_co]));
        return (new SlackMessage)
            ->error()
            ->from($github->committer->login.' ha generado un Error')
            ->image($github->committer->avatar_url)
            ->to('#pila-versionamiento')
            ->content("_".$github->commit->message."_")
            ->attachment(function ($attachment) use ($url,$github) {
                $attachment->title("Ver detalle", $url)
                    ->fields([
                        'Aplicación' => $this->nuevo->app_co,
                        'Culpable' => $github->commit->author->name,
                        'Commit' => $github->commit->url,
                    ]);
            });
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
       //
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
