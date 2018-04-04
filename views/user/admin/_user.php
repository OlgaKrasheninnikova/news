<?php


/**
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\User $user
 */
?>

<?= $form->field($user, 'email')->textInput(['maxlength' => 255, 'id' => 'email-'.$user->id]) ?>
<?= $form->field($user, 'username')->textInput(['maxlength' => 255, 'id' => 'username-'.$user->id]) ?>
<?= $form->field($user, 'password')->passwordInput(['id' => 'password-'.$user->id]) ?>
<?= $form->field($user, 'notifications_email')->checkbox(['id' => 'notifications_email-'.$user->id]) ?>
<?= $form->field($user, 'notifications_push')->checkbox(['id' => 'notifications_push-'.$user->id]) ?>
