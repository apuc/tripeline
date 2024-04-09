<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

/*
Class Country
 *
 * @property \App\Models\Country $model
*
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Message extends Section implements Initializable {
    /* @var bool */
    protected $checkAccess = false;

    /* @var string */
    protected $title;

    /* @var string */
    protected $alias;

    /* Initialize class.*/
    public function initialize() {
        $this->addToNavigation()->setPriority(600)->setIcon('fas fa-comments');
    }

    /* @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay( $payload = [] ) {
        $columns = [];

        $display = AdminDisplay::table()
            ->setApply(function ($query) {
                $query->groupBy('user_id');
            })
            ->setColumns(
                AdminColumn::text('user.email', 'User'),
                AdminColumn::custom( 'New messages', function ($model) {
                    if ($model->user) {
                        $newMessages = \App\Models\Message::where([['user_id', $model->user->id], ['status', 0]])->get()->count();
                        $color = $newMessages > 0 ? 'red' : 'black';
                        return "<span style='color: ".$color."'>$newMessages</span>";
                    }
                } ),
                AdminColumn::datetime('created_at', 'Date')->setWidth('150px')
            )->paginate(25);

        return $display;
    }

    /* @param int|null $id
     * @param array    $payload
     *
     * @return string
     */
    public function onEdit( $id = null, $payload = [] ) {
        date_default_timezone_set('UTC');
        $chatID = \App\Models\Message::find($id)->chat_id;
        $userID = \App\Models\Message::find($id)->user_id;
        $messages = \App\Models\Message::where('chat_id', $chatID)->get();
        $messagesHtml = '<div class="admin__chat"><div class="messages">';

        foreach ($messages as $message) {
            $style = 'msg-user';
            if ($message->user_id !== $userID) {
                $style = 'msg-admin';
            }
            $messagesHtml .= '<div class="msg ' . $style . '"><div class="msg-item"><div class="msg-time"><span>' . $message->created_at . '</span></div><div class="msg-text"><span>' . $message->message . '</span></div></div></div>';
        }

        $messagesHtml .= '</div><div class="msg-send"><textarea class="msg-data" name="message"></textarea><span class="send-chat">Send message</span></div></div>';
        $messagesHtml .= '<script>const socket = new WebSocket("wss://api.drivermytripline.com/wss");';
        $messagesHtml .= 'socket.onopen = function () { socket.send(JSON.stringify({ connect_id: 1, chat_user: ' . $userID . ' }))};';
        $messagesHtml .= 'function sendMsg(msg) { let message = document.querySelector(".msg-data").value; fetch("/api/message/add", { method: "post", headers: { "Content-Type": "application/json", "Accept": "application/json", "X-Requested-With": "XMLHttpRequest", "Access-Control-Allow-Origin": "*" },  body: JSON.stringify({chat_id: ' . $chatID . ', user_id: 1, message: message})}).then(resp =>{ console.log(resp); if (resp.status) {socket.send(JSON.stringify({ msg: msg, user_id: ' . $userID . ' }))}});}';
        $messagesHtml .= 'addEventListener("DOMContentLoaded", () => { document.querySelector(".send-chat").addEventListener("click", function() { sendMsg(document.querySelector(".msg-data").value); setTimeout(() =>{ location.reload() }, 2000) }); }); </script>';

        return $messagesHtml;
    }

    /* @return FormInterface */
    public function onCreate( $payload = [] ) {
        return $this->onEdit( null, $payload );
    }

    /* @return bool */
    public function isDeletable( Model $model ) {
        return false;
    }

    /* @return void */
    public function onRestore( $id ) {
        // remove if unused
    }
}
