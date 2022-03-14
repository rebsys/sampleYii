<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user signup form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {
	public $username;
	public $password;
	public $rememberMe;
  public $twofaCode;

	private $_identity;

  /**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
			array('twofaCode', 'twofaCode'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe' => 'Remember me next time',
      'twofaCode' => '2FA Code if you use it',
		);
	}
  
  public function twofaCode($attribute, $params) {
    if(!$this->hasErrors()) {
      $user = new User();
      $userData = $user->findUserByLogin($this->username)->read();
      if (
          !empty($userData['twofa_state'])
          and !$user->checkTwofaCode($this->username, $this->twofaCode)
      ) {
        $this->addError('twofaCode', 'Incorrect code.');
        Yii::log('2FA code: ' . (new PragmaRX\Google2FA\Google2FA())->getCurrentOtp($userData['twofa_key']));
      }
    }
  }
  
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 * @param string $attribute the name of the attribute to be validated.
	 * @param array $params additional parameters passed with rule when being executed.
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors()) {
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
