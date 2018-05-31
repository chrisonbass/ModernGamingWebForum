<div class="container">
<?php foreach ( $properties as $prop ): ?>
  <div> 
    <strong><?=$prop['label']?></strong> : <em><?=$prop['value']?></em>
  </div> 
<?php endforeach; ?>
</div>
