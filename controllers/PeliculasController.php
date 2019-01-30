<?php

namespace app\controllers;

use app\models\Generos;
use app\models\Peliculas;
use app\models\PeliculasSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Definición del controlador peliculas.
 */
class PeliculasController extends \yii\web\Controller
{
    public function actionPrueba()
    {
        $provider = new ActiveDataProvider([
            'query' => Peliculas::find(),
            'pagination' => [
                'pageSize' => 2,
            ],
            'sort' => [
                'attributes' => [
                    'titulo',
                    'anyo',
                ],
            ],
        ]);
        var_dump($provider->models);
        var_dump($provider->count);
        var_dump($provider->totalCount);
    }

    public function actionIndex()
    {
        $searchModel = new PeliculasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $pelicula = new Peliculas();

        if ($pelicula->load(Yii::$app->request->post()) && $pelicula->save()) {
            return $this->redirect(['peliculas/index']);
        }
        return $this->render('create', [
            'pelicula' => $pelicula,
        ]);
    }

    public function actionVer($id)
    {
        $pelicula = $this->buscarPelicula($id);

        $participantes = (new \yii\db\Query())
            ->select(['personas.nombre', 'papeles.papel'])
            ->from('participaciones')
            ->innerJoin('personas', 'persona_id = personas.id')
            ->innerJoin('papeles', 'papel_id = papeles.id')
            ->where(['pelicula_id' => $pelicula->id])
            ->all();

        $participantes = ArrayHelper::index($participantes, null, 'papel');

        return $this->render('ver', [
            'pelicula' => $pelicula,
            'participantes' => $participantes,
        ]);
    }

    public function actionUpdate($id)
    {
        $pelicula = $this->buscarPelicula($id);

        if ($pelicula->load(Yii::$app->request->post()) && $pelicula->save()) {
            return $this->redirect(['peliculas/index']);
        }

        return $this->render('update', [
            'pelicula' => $pelicula,
            'listaGeneros' => $this->listaGeneros(),
        ]);
    }

    public function actionDelete($id)
    {
        $this->buscarPelicula($id)->delete();
        return $this->redirect(['peliculas/index']);
    }

    private function listaGeneros()
    {
        return Generos::find()
            ->select('genero')
            ->indexBy('id')
            ->column();
    }

    private function buscarPelicula($id)
    {
        $fila = Peliculas::find()
            ->where(['id' => $id])
            ->with([
                'participaciones.persona',
                'participaciones.papel',
            ])
            ->one();
        if ($fila === null) {
            throw new NotFoundHttpException('Esa película no existe.');
        }
        return $fila;
    }
}
