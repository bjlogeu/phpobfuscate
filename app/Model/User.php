<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
  public $hasMany = array (
    'File' => array (
      'className' => 'File',
      'foreignKey' => 'user_id',
      'dependent' => true
    )
  );

  public $validate = array(
      'email' => array (
        'is_email' => array (
          'rule' => 'email',
          'required' => true,
          'message' => 'Invalid email!'
        ),
        'unique' => array (
          'rule' => 'isUnique',
          'required' => true,
          'message' => 'Email already taken!'
        ),
      ),
      'password' => array (
        'length' => array (
          'rule' => array ('minlength', 8),
          'required' => true,
          'message' => 'Minimum password length is 8!'
        ),
        'confirm' => array (
          'rule' => array ('equalToField', 'password_confirmation'),
          'message' => 'Passwords don\'t match!',
          'required' => true
        )
      )
    );

  public function equalToField($check, $otherfield) {
    $fname = '';
    foreach ($check as $key => $value){
        $fname = $key;
        break;
    }
    return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname];
	//check if passwords match
  }

  public function beforeSave($options = array()) {
    if (isset($this->data[$this->alias]['password'])) {
        $passwordHasher = new SimplePasswordHasher();
        $this->data[$this->alias]['password'] = $passwordHasher->hash(
            $this->data[$this->alias]['password']
        );
    }
    return true;
  }
}
