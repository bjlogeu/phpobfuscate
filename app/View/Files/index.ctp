<table>
  <tr>
    <th>File</th>
    <th>Size</th>
    <th>Uploaded at</th>
  </tr>
<?php
  foreach($files as $file) {
  ?>
     <tr>
       <td><?php echo $this->Html->link($file['File']['name'], '/files/download/?file='.$file['File']['id']); ?></td>
       <td><?php echo round($file['File']['file_size']/1024, 2); ?></td>
       <td><?php echo $file['File']['uploaded']; ?></td>
     </tr>
<?php
  }
?>
</table>
