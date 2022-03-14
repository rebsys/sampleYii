<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
    $this->errorCode = self::ERROR_NONE;
    /** @var CDbCommand $command */
//    $command = Yii::app()->db->createCommand("select id, password from tbl_user where username = :username");
//    $dataReader = $command->query(['username' => $this->username]);
    try {
      $user = new User();
      if (empty($user->findUserByLogin($this->username))) {
        throw new UserIdentityException('User not found', self::ERROR_USERNAME_INVALID);
      }
//      $passwordHash = $dataReader->read()['password'];
//      if (!CPasswordHelper::verifyPassword($this->password, $passwordHash)) {
      if (!$user->checkPassword($this->username, $this->password)) {
        throw new UserIdentityException('Password not correct', self::ERROR_USERNAME_INVALID);
      }
    } catch (UserIdentityException $e) {
      $this->errorCode = $e->getCode();
    }
    return !$this->errorCode;
	}
}