<?php if ( $model && $model->id ): ?>
  <p>
    Are you sure you want to delete this topic you made in <a href="index.php?controller=board&action=view&id=<?=$board->id?>" title="<?=addslashes($board->name)?>">this board</a>?
  </p>
  <div class="p-3 mb-2 bg-light text-dark">
    <?=$model->title?>
    <p><?=$model->body?></p>
  </div>
  <br />
  <form action="index.php" method="post">
    <input type="hidden" name="controller" value="topic" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id" value="<?=$model->id?>" />
    <button class="btn btn-danger" type="submit" name="delete" value="yes">
      Delete
    </button>
    <button class="btn btn-info" type="submit" name="delete" value="no">
      Cancel
    </button>
  </form>
<?php else: ?>
  <p>Invalid Topic</p>
  <p><a href="index.php?controller=board">Return to board listing</a>
<?php endif; ?>
