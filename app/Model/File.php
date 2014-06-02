<?php
class File extends AppModel {
  public $belongsTo = 'User';
  //променливата е от кейка

  public $actsAs = array(
  // нек'в кейк плъгин , който ъплоудва файлове
    'Upload.Upload' => array(
      'file' => array(
        'fields' => array(
          'type' => 'file_type',
          'size' => 'file_size'
        ),
        'pathMethod' => 'flat',
		// нямам идея GOOOGLE BITCH
        'path' => '{ROOT}{DS}webroot{DS}files{DS}',
		//root,DS - php cake константи
        'extensions' => 'php'
		// ограничава ъплоуда на други боклуци в плъгина
      )
    )
  );

  public $validate = array (
    'file' => array (
      'file_upload' => array (
        'rule' => 'isFileUpload',
        'message' => 'File was missing from submission'
		//За да не се пусне празна форма
      ),
      'below_max_size' => array (
        'rule' => array('isBelowMaxSize', 10240),
		// ограничава размера на файловете
        'message' => 'File is larger than the maximum filesize'
      ),
      'valid_extension' => array (
        'rule' => array('isValidExtension', array('php')),
		// ограничава да са само php файлове (за полетата в базата данни)
        'message' => "Can only obfuscate php files!"
      ),
    )
  );

  public function beforeSave($options = array()) {
    $this->data['File']['file_dir'] = ROOT.DS.WEBROOT_DIR.DS.'files'.DS;
    $this->data['File']['name'] = $this->data['File']['file'];
    $this->data['File']['file'] = md5(time().$this->data['File']['file']).".php";
    $this->data['File']['uploaded'] = date('c');
    return $this->data['File']['user_id'] == AuthComponent::user('id');
	// взимам времето и датата и им давам md5,
	//за да станат с нек'во яко име, иначе курец , ако кача 2 еднакви файла с едно и също име
  }
}
