<?php
/* @var $this SiteController */
/* @var $model SignupForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Signup';
$this->breadcrumbs = array(
    'Signup',
);
?>

<h1>Signup</h1>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
  <?php $form = $this->beginWidget('CActiveForm', [
          'id' => 'signup-form',
          'enableClientValidation' => true,
          'clientOptions' => [
              'validateOnSubmit' => true,
          ],
      ]
  ); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <div class="row">
    <?php echo $form->labelEx($model, 'username'); ?><?php echo $form->textField($model, 'username'); ?><?php echo $form->error($model, 'username'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model, 'password'); ?><?php echo $form->passwordField($model, 'password'); ?><?php echo $form->error($model, 'password'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model, 'passwordConfirm'); ?><?php echo $form->passwordField($model, 'passwordConfirm'); ?><?php echo $form->error($model, 'passwordConfirm'); ?>
  </div>

  <div class="row rememberMe">
    <?php echo $form->checkBox($model,'require2Fa'); ?>
    <?php echo $form->label($model,'require2Fa'); ?>
    <?php echo $form->error($model,'require2Fa'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Signup'); ?>
  </div>
  
  <?php $this->endWidget(); ?>
</div><!-- form -->
