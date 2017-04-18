<?php

namespace app\components;

use yii\base\Widget;
use yii\helpers\Url;

class LastGamesWidget extends Widget
{

    public function init()
    {
        parent::init();
        $y = 'Чемпион!!!';

    }

    public function run()
    {

        return $this->render('lastgame', [
            'cont' => $y,
        ]);
    }

}