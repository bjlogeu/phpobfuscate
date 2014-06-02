<div class="users form">
<?php echo $this->Form->create('User'); ?>
  <fieldset>
    <legend><?php echo __('Add User'); ?></legend>
      <?php
        echo $this->Form->input('email');
        echo $this->Form->input('password');
        echo $this->Form->input('password_confirmation', array (
          'label' => 'Confirm password',
          'type' => 'password'
        ));
      ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
