<?php echo $this->Form->create('File', array('type' => 'file')); ?>
    <?php echo $this->Form->input('file', array('type' => 'file')); ?>
    <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => AuthComponent::user('id'))); ?>
<?php echo $this->Form->end('Upload'); ?>
