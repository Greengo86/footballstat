<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 01.12.2016
 * Time: 16:29
 */

namespace app\modules\admin\models;


use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $team_embl;

    public function rules()
    {
        return [
            [['team_embl'], 'file', 'extensions' => 'png, jpg'],
        ];
    }

}