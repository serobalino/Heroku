<?php

namespace App\Notifications;

use App\Commits;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SlackErrorGh extends Notification
{
    use Queueable;

    protected $nuevo;
    protected $branch;
    protected $error;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Commits $nuevo,$branch,$error)
    {
        $this->nuevo=$nuevo;
        $this->branch=$branch;
        $this->error=$error;
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
        $action =   $this->nuevo->respuesta_co;
        $url    =   $this->nuevo->log_co;
        $branch =   $this->branch;
        return (new SlackMessage)
            ->error()
            ->from(@$github->committer->login.' ha generado un Error')
            ->image(@$github->committer->avatar_url ? $github->committer->avatar_url : ":boom:")
            ->to('#pila-versionamiento')
            ->content("<!here> _".$github->commit->message."_")
            ->attachment(function ($attachment) use ($url,$branch,$github,$action) {
                $attachment
                    ->fields([
                        'Aplicación' => $action->event->repository->name,
                        'Rama' => $branch,
                        'Culpable' => $github->commit->author->name,
                        'Commit' => $github->html_url,
                    ])
                    ->content($this->error)
                    ->action('Ver detalle de error', url($url),'danger');
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
