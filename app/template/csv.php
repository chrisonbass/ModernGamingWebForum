<div class="container csv-editor">
  <h1>CSV Editor</h1>
  <?=$alert?>
  <form method="post" action="index.php?controller=csv-editor&action=update">
    <div class="table-responsive">
      <?=$children?>
    </div>
    <div class="text-right">
      <button class="btn" type="submit" name="action" value="add-row">
        Add Row
      </button>
      <button class="btn btn-primary" type="submit" name="action" value="update">
        Update
      </button>
    </div>
  </form>
</div>
