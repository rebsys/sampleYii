<?php

/**
 * SignupForm class.
 * SignupForm is the data structure for keeping
 * user signup form data. It is used by the 'signup' action of 'SiteController'.
 */
class SignupForm extends CFormModel {
  
  public $username;
  public $password;
  public $passwordConfirm;
  public $require2Fa;
  
  public function rules() {
    return [
        ['username, password, passwordConfirm', 'required'],
        ['username, password, passwordConfirm', 'characterRange'],
        ['require2Fa', 'boolean'],
        ['username', 'usernameUnique'],
        ['password, passwordConfirm', 'passwordsCorrect'],
    ];
  }
  
  public function attributeLabels() {
    return array(
        'require2Fa' => 'Use 2FA for authentication',
    );
  }

  public function characterRange($attribute, $param) {
    if (!preg_match('/^[A-z0-9]{5,30}$/', $this->{$attribute})) {
      $this->addError($attribute, 'Use only alphabetic characters and numbers from 5 to 30 in length.');
    }
  }
  
  public function usernameUnique($attribute, $params) {
    if (!$this->hasErrors()) {
      if (Yii::app()->userManager->findUserByLogin($this->username)->read()) {
        $this->addError('username', 'The username is already exists.');
      }
    }
  }
  
  public function passwordsCorrect($attribute, $params) {
    if (!$this->hasErrors()) {
      if ($this->password != $this->passwordConfirm) {
        $this->addError('passwordConfirm', 'The passwords are mismatch.');
      }
    }
  }
  
}