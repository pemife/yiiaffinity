<?php

namespace app\models;

use yii\base\Model;

/**
 * Modelo que estamos creando para la tabla de participantes y roles
 * tablas que no estan incluidas en este yiiaffinity.
 */
class ParticipantesForm extends Model
{
    public $persona_id;
    public $rol_id;

    public function rules()
    {
        return[
            [['persona_id', 'rol_id'], 'required'],
            [['persona_id', 'rol_id'], 'integer'],
        ];
    }
}
