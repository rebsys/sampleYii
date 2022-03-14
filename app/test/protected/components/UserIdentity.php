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
    try {
      if (empty(Yii::app()->userManager->findUserByLogin($this->username))) {
        throw new UserIdentityException('User not found', self::ERROR_USERNAME_INVALID);
      }
      if (!Yii::app()->userManager->checkPassword($this->username, $this->password)) {
        throw new UserIdentityException('Password not correct', self::ERROR_USERNAME_INVALID);
      }
    } catch (UserIdentityException $e) {
      $this->errorCode = $e->getCode();
    }
    return !$this->errorCode;
	}
}