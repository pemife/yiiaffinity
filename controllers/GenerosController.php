<?php

namespace app\controllers;

use app\models\GenerosForm;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Definición del controlador generos.
 */
class GenerosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['update'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    // Esto no entra en el examen, es parte del segundo trimestre

    public function actionIndex()
    {
        $count = Yii::$app->db
                ->createCommand('SELECT count(*) FROM generos')
                ->queryScalar();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $count,
        ]);

        $filas = Yii::$app->db
            ->createCommand('SELECT g.*, count(p.id) AS cantidad
                               FROM generos g
                          LEFT JOIN peliculas p
                                 ON g.id = p.genero_id
                           GROUP BY g.id
                           ORDER BY genero
                              LIMIT :limit
                             OFFSET :offset', [
                ':limit' => $pagination->limit,
                ':offset' => $pagination->offset,
                ])->queryAll();
        return $this->render('index', [
            'filas' => $filas,
            'pagination' => $pagination,
            // 'peliculasGenero' => $this->mapGenerosPeliculas(),
        ]);
    }

    public function actionCreate()
    {
        $generosForm = new GenerosForm();

        if ($generosForm->load(Yii::$app->request->post()) && $generosForm->validate()) {
            Yii::$app->db->createCommand()
                ->insert('generos', $generosForm->attributes)
                ->execute();
            Yii::$app->session->setFlash('success', 'Fila insertada correctamente.');
            return $this->redirect(['generos/index']);
        }
        return $this->render('create', [
            'generosForm' => $generosForm,
        ]);
    }

    public function actionUpdate($id)
    {
        $generosForm = new GenerosForm(['attributes' => $this->buscarGenero($id)]);

        if ($generosForm->load(Yii::$app->request->post()) && $generosForm->validate()) {
            Yii::$app->db->createCommand()
                ->update('generos', $generosForm->attributes, ['id' => $id])
                ->execute();

            Yii::$app->session->setFlash('success', 'Fila modificada correctamente.');
            return $this->redirect(['generos/index']);
        }

        return $this->render('update', [
            'generosForm' => $generosForm,
            'listaGeneros' => $this->listaGeneros(),
        ]);
    }

    public function actionDelete($id)
    {
        $count = Yii::$app->db
        ->createCommand('SELECT count(*)
                           FROM peliculas
                          WHERE genero_id = :id', [':id' => $id])
                          ->queryScalar();
        // Aqui limito a una sola fila, y un solo campo por optimizacion
        // QueryScalar devuelve el primer valor de la primera fila

        if ($count == 0) {
            Yii::$app->db->createCommand()->delete('generos', ['id' => $id])->execute();
            Yii::$app->session->setFlash('success', 'Fila borrada correctamente.');
        } else {
            Yii::$app->session->setFlash('danger', 'Existe una pélicula con ese género!.');
        }
        return $this->redirect(['generos/index']);
    }

    private function listaGeneros()
    {
        $generos = Yii::$app->db->createCommand('SELECT * FROM generos')->queryAll();
        $listaGeneros = [];
        foreach ($generos as $genero) {
            $listaGeneros[$genero['id']] = $genero['genero'];
        }
        return $listaGeneros;
    }

    private function buscarGenero($id)
    {
        $fila = Yii::$app->db
            ->createCommand('SELECT *
                               FROM generos
                              WHERE id = :id', [':id' => $id])->queryOne();
        if (empty($fila)) {
            throw new NotFoundHttpException('Ese género no existe.');
        }
        return $fila;
    }

    // Aproximacion a cantidad peliculas de un genero
    // private function mapGenerosPeliculas()
    // {
    //     $generos = $this->listaGeneros();
    //     var_dump($generos);
    //     $numPeliculasGenero = [];
    //     foreach ($generos as $id => $genero) {
    //         $numPeliculasGenero[$genero] = Yii::$app->db
    //             ->createCommand('SELECT count(*)
    //                                FROM peliculas p
    //                                JOIN generos g
    //                                  ON p.genero_id = g.id
    //                               WHERE p.genero_id = :id', [':id' => $id])
    //             ->queryScalar();
    //     }
    //     return $numPeliculasGenero;
    // }
}
