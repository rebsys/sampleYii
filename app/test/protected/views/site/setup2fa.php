<?php
/* @var $this SiteController */
/* @var $model Setup2faForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Setup2fa';
$this->breadcrumbs = array(
    'Setup2fa',
);
?>

<h1>Setup two-factory authentication</h1>

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
  
  <?php echo $model->google2faImgBase64; ?>
  
  <p>
    <?php echo $model->google2faKey; ?>
  </p>

  <div class="row">
    <?php echo $form->labelEx($model, 'google2faTest'); ?><?php echo $form->textField($model, 'google2faTest'); ?><?php echo $form->error($model, 'google2faTest'); ?>
  </div>

  <div class="row rememberMe">
    <?php echo $form->checkBox($model,'require2Fa'); ?>
    <?php echo $form->label($model,'require2Fa'); ?>
    <?php echo $form->error($model,'require2Fa'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Enable'); ?>
  </div>
  
  <?php $this->endWidget(); ?>
</div><!-- form -->
