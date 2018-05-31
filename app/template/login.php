<?php
$errors = $model->getErrors();
?>
<form class="form" action="index.php?controller=login" method="post">
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" name="username" class="<?=(isset($errors['username']) ? "is-invalid form-control" : "form-control" )?>" id="username-field" placeholder="Enter username" value="<?=$model->username?>" />
    <?php if ( isset($errors['username']) ): ?>
    <div class="invalid-feedback"><?=implode(" ", $errors['username'])?></div>
    <?php endif; ?>
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" name="password" class="<?=(isset($errors['password']) ? "is-invalid form-control" : "form-control" )?>" id="username-field" placeholder="Enter password" />
    <?php if ( isset($errors['password']) ): ?>
    <div class="invalid-feedback"><?=implode(" ", $errors['password'])?></div>
    <?php endif; ?>
  </div>
  <div class="form-group">
    <button class="btn btn-primary" name="submit" value="submit">
      Login
    </button>
    <button class="btn btn-danger" name="submit" value="cancel">
      Cancel
    </button>
  </div>
</form>
