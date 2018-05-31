<form class="form" action="index.php?controller=topic&action=create" method="post">
  <div class="form-group">
    <label>Title</label>
    <input class="form-control" name="title" value="<?=$model->title?>" />
  </div>
  <div class="form-group">
    <label>Body</label>
    <textarea class="form-control" name="body">
      <?=$model->body?>
    </textarea>
  </div>
  <div class="form-group">
    <button type="submit" class="btn btn-primary" name="submit" value="submit">
      Create
    </button>
    <button class="btn btn-info" type="submit" name="submit" value="cancel">
      Cancel
    </button>
  </div>
  <input type="hidden" name="board_id" value="<?=$model->board_id?>" />
  <input type="hidden" name="created_by" value="<?=$model->created_by?>" />
</form>
