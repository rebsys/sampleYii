<?php

use PragmaRX\Google2FA\Google2FA;

class UserManager extends CComponent {
  
  const createTableSql =
"create table tbl_user (
  id integer primary key autoincrement,
  username varchar(30) not null unique,
  password varchar(100) not null,
  twofa_key varchar(40),
  twofa_state integer
)";

  public function init() {
    $this->initTables();
  }
  
  
  /**
   * Finds an existing user by login
   * @param $login
   * @return CDbDataReader|mixed
   * @throws CException
   */
  public function findUserByLogin($login) {
    $command = Yii::app()->db->createCommand("select * from tbl_user where username = :username");
    return $command->query([':username' => $login]);
  }
  
  /**
   * Creates a new user
   * @param $username
   * @param $password
   * @return void
   * @throws CException
   */
  public function createUser($username, $password) {
    $command = Yii::app()->db->createCommand("insert into tbl_user (username, password, twofa_state) values (:username, :password, 0)");
    $command->execute(['username' => $username, 'password' => CPasswordHelper::hashPassword($password)]);
  }
  
  /**
   * Checks the password for an existing user
   * @param $username
   * @param $password
   * @return bool
   * @throws CException
   */
  public function checkPassword($username, $password) {
    $result = false;
    $user = $this->findUserByLogin($username);
    if ($user) {
      $result = CPasswordHelper::verifyPassword($password, $user->read()['password']);
    }
    return $result;
  }
  
  /**
   * Generates a new 2FA key, updates for the $username, and returns it.
   * @param $username
   * @return string
   * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
   * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
   */
  public function updateTwofaKey($username) {
    $key = (new Google2FA())->generateSecretKey();
    $command = Yii::app()->db->createCommand("update tbl_user set twofa_state = 0, twofa_key = :key where username = :username");
    $command->execute(['username' => $username, 'key' => $key]);
    return $key;
  }
  
  /**
   * Updates 2FA state for the $username
   * @param $username
   * @param $state
   * @return void
   */
  public function updateTwofaState($username, $state) {
    $command = Yii::app()->db->createCommand("update tbl_user set twofa_state = :state where username = :username");
    $command->execute(['username' => $username, 'state' => (bool) $state]);
  }
  
  /**
   * Verifies 2FA code
   * @param $username
   * @param $code
   * @return bool|int
   * @throws CException
   */
  public function checkTwofaCode($username, $code) {
    $result = false;
    $user = $this->findUserByLogin($username);
    if ($user) {
      try {
        $result = (new Google2FA())->verifyKey($user->read()['twofa_key'], $code);
      } catch (Exception $e) {
        Yii::trace($e->getMessage());
      }
    }
    return $result;
  }
  
  protected function initTables() {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT name FROM sqlite_master WHERE type='table' AND name='tbl_user'");
    if (!$command->queryRow()) {
      $createCommand = $connection->createCommand(self::createTableSql);
      $createCommand->execute();
    }
  
  }
  
}