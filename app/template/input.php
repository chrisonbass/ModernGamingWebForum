<?php if ( $type == "text" || $type == "hidden" ): ?>
  <input class="form-control" type="<?=$type?>" name="<?=$name?>" value="<?=$value?>" id="<?=$id?>" />
<?php elseif ($type == "button" ): ?>
  <button class="btn <?=$bs_class?>" type="<?=$button_type?>" name="<?=$name?>" value="<?=$value?>" id="<?=$id?>">
    <?=$label?>
  </button>
<?php elseif ($type == "select" ): ?>
  <select  name="<?=$name?>" class="form-control" value="<?=$value?>" id="<?=$id?>" />
    <?php foreach ( $options as $option ): ?>
      <option value="<?=$option['value']?>"><?=$option['label']?></option>
    <?php endforeach; ?>
  </select>
<?php endif; ?>
