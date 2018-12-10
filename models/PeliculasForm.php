<?php

namespace app\models;

use yii\base\Model;

class PeliculasForm extends Model
{
    public $titulo;
    public $anyo;
    public $duracion;
    public $genero_id;

    public function rules()
    {
        return [
            [['titulo', 'genero_id'], 'required'],
            [['genero_id', 'anyo'], 'integer', 'min' => 0],
            [['duracion'], 'integer', 'min' => 0, 'max' => 9999],
            [['titulo'], 'string', 'max' => 255],
            [['anyo'], 'number', 'max' => (date('Y') + 10)],
        ];
    }

    public function attributeLabels()
    {
        return [
            'titulo' => 'Título',
            'anyo' => 'Año',
            'duracion' => 'Duración',
            'genero_id' => 'Género',
        ];
    }
}
