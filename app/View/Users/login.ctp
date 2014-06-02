<div class="users form">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
  <fieldset>
    <legend>
      <?php echo __('Please enter your username and password'); ?>
    </legend>
    <?php
      echo $this->Form->input('email');
      echo $this->Form->input('password');
    ?>
  </fieldset>
<?php
  echo $this->Form->end(__('Login'));
  echo "Don't have an account? ".$this->Html->link('register', '/users/add');
?>
  <br />
  <br />
  <h2>Or you can just obfuscate a file without an account</h2>
  <br />
  <form action="/index.php/files/obfuscate" id="FileObfuscateForm" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="input file required">
      <label for="FileFile">File</label>
        <input type="file" name="data[File][file]"  id="FileFile" required="required"/>
    </div>
    <div class="submit">
      <input  type="submit" value="Upload"/>
    </div>
  </form>
</div>
