<?php

namespace App\Notifications;

use App\Commits;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SlackExitoGh extends Notification
{
    use Queueable;

    protected $nuevo;
    protected $branch;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Commits $nuevo,$branch)
    {
        $this->nuevo=$nuevo;
        $this->branch=$branch;
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
        $branch =   $this->branch;
        $url    =   url(route("generar", ["app" => $this->nuevo->app_co, "commit" => $this->nuevo->id_co]));
        return (new SlackMessage)
            ->success()
            ->from($github->commit->author->name)
            ->image(@$github->committer->avatar_url)
            ->to('#ips-236-alcance-portal-pagos-web')
//            ->to('#pila-versionamiento')
            ->content("_".$github->commit->message."_")
            ->attachment(function ($attachment) use ($url,$branch,$github,$action) {
                $attachment->title("Ver detalle", $url)
                    ->fields([
                        'Aplicación' => $action->event->repository->name,
                        'Rama' => $branch,
                        'Commit' => $github->html_url,
                        'Versión' => @Commits::where('app_co',$this->nuevo->app_co)->where('estado_co',$this->nuevo->estado_co)->count(),
                    ])
                    ->action('DESCARGAR DIST', $url,'primary');
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
