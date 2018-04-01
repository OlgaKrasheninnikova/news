<?php


/**
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\User $user
 */
?>
<?= get_class( $form ) ?>
<?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'username')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'password')->passwordInput() ?>
<?= $form->field($user, 'notifications_email')->checkbox() ?>
<?= $form->field($user, 'notifications_push')->checkbox() ?>
