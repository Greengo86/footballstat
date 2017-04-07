<?php

namespace app\commands\events;

use yii\base\Event;

//Класс для передачи обработчику дополнительной информации в письме! Наследуемся от yii\base\Event
class PlayInsertEvent extends Event
{
    public $play_insert;
}