<?php
require(APPLIBS.'Obfuscator.php');

class FilesController extends AppController {

  public function beforeFilter() {
    $this->Auth->allow(['obfuscate']);
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->File->create();
      if ($this->File->save($this->request->data)) {
        $obf = new Obfuscator();
        $f = $this->File->find('first', ['conditions' => ['File.id' => $this->File->getInsertID()]]);
        $this->response->body($obf->obfuscate(WWW_ROOT.'files'.DS.$f['File']['file']));
        $this->response->download($this->request->data['File']['file']['name'].'.obf');
        return $this->response;
      } else {
        $this->Session->setFlash('Unable to upload.');
      }
    }
  }

  public function index() {
   $this->set('files', $this->File->find('all',
      array(
        'conditions' => array (
          'File.user_id' => $this->Auth->user('id')
        )
      )
    ));
   }

  public function download() {
    $id = $this->request->query('file');
    $file = $this->File->find('first', ['conditions' => ['File.id' => $id]]);
    if(!empty($file)) {
      $obf = new Obfuscator();
      $this->response->body($obf->obfuscate(WWW_ROOT.DS.'files'.DS.$file['File']['file']));
      $this->response->download($file['File']['name'].'.obf');
      return $this->response;
	  // намира файла от качените и го сваля
    }
    return $this->redirect(['controller' => 'files', 'action' => 'index']);
  }

  public function obfuscate() {
    if ($this->request->is('post')) {
      $obf = new Obfuscator();
      $this->response->body($obf->obfuscate($this->request->data['File']['file']['tmp_name']));
      $this->response->download($this->request->data['File']['file']['name'].'.obf');
      return $this->response;
	  // Обфускира файла
    }
//    return $this->redirect(['controller' => 'users', 'action' => 'login']);
  }
}
