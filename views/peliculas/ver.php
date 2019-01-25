<?php
use yii\helpers\Html;

$this->title = 'Ver una película';
$this->params['breadcrumbs'][] = $this->title;
?>
<<<<<<< HEAD
<dl class="dl-horizontal">
    <dt>Título</dt>
    <dd><?= Html::encode($pelicula->titulo) ?></dd>
</dl>
<dl class="dl-horizontal">
    <dt>Año</dt>
    <dd><?= Html::encode($pelicula->anyo) ?></dd>
</dl>
<dl class="dl-horizontal">
    <dt>Duración</dt>
    <dd><?= Html::encode($pelicula->duracion) ?></dd>
</dl>
<dl class="dl-horizontal">
    <dt>Género</dt>
    <dd><?= Html::encode($pelicula->genero->genero) ?></dd>
</dl>

<?php foreach ($participantes as $papel => $personas): ?>
    <dl class="dl-horizontal">
        <dt><?= Html::encode($papel) ?></dt>
        <?php foreach ($personas as $persona): ?>
            <dd><?= Html::encode($persona['nombre']) ?></dd>
        <?php endforeach ?>
    </dl>
<?php endforeach ?>
=======
<?php foreach ($pelicula->participaciones as $participacion): ?>
    <dl>
        <dt>Nombre</dt>
        <dd><?= $participacion->persona->nombre ?></dd>
        <dt>Papel</dt>
        <dd><?= $participacion->papel->papel ?></dd>
    </dl>
<?php endforeach ?>

<?php $form = ActiveForm::begin(['enableClientValidation' => false]) ?>
    <?= $form->field($pelicula, 'titulo', $inputOptions) ?>
    <?= $form->field($pelicula, 'anyo', $inputOptions) ?>
    <?= $form->field($pelicula, 'duracion', $inputOptions) ?>
    <?= $form->field($pelicula, 'genero_id', $inputOptions) ?>
    <div class="form-group">
        <?= Html::a('Volver', ['peliculas/index'], ['class' => 'btn btn-danger']) ?>
    </div>
<?php ActiveForm::end() ?>
>>>>>>> d45c38119ed18f1e03fe791b0f729313aa4d2a87
