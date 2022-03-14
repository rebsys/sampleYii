<?php

use PragmaRX\Google2FA\Google2FA;

/**
 * SignupForm class.
 * SignupForm is the data structure for keeping
 * user signup form data. It is used by the 'signup' action of 'SiteController'.
 */
class Setup2faForm extends CFormModel {
  
  public $google2faTest;
  public $google2faKey;
  public $google2faImgBase64;
  public $require2Fa;
  
  protected $user;
  private $_id;
  
  public function __construct($scenario = '') {
    parent::__construct($scenario);
    $this->user = new User();
    $this->_id = Yii::app()->user->getId();
    
    $userData = $this->user->findUserByLogin($this->_id)->read();
    $this->require2Fa = $userData['twofa_state'];
    if (empty($userData['twofa_key'])) {
      $this->google2faKey = $this->user->updateTwofaKey($this->_id);
    } else {
      $this->google2faKey = $userData['twofa_key'];
    }
    
    Yii::log('2FA code: ' . (new Google2FA())->getCurrentOtp($this->google2faKey));

    $renderer =
        (new BaconQrCode\Renderer\Image\Svg())
            ->setWidth(200)
            ->setHeight(200);
    $writer = new BaconQrCode\Writer($renderer);
    $this->google2faImgBase64 =
        $writer->writeString(
            (new Google2FA())->getQRCodeUrl(
                Yii::app()->name,
                $this->_id,
                $this->google2faKey
            )
        );
  }
  
  public function rules() {
    return [
        ['require2Fa', 'boolean'],
        ['google2faTest', 'required'],
        ['google2faTest', 'google2faCheck']
    ];
  }
  
  public function attributeLabels() {
    return array(
        'google2faKey' => 'The google secret Key',
        'google2faTest' => 'The 2FA code for testing this method',
    );
  }
  
  public function google2faCheck($attribute, $param) {
    if (!$this->hasErrors()) {
      if (!$this->user->checkTwofaCode($this->_id, $this->{$attribute})) {
        $this->addError($attribute, 'This code is not correct.');
      }
    }
  }
  
}