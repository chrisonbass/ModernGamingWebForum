<?php if ( $board && $board->id ): ?>
  <div class="jumbotron">
    <h1 class="display-4"><?=$board->name?></h1>
    <p class="lead"><?=$board->description?></p>
  </div>
  <h2>Topics
  <?php if ( $can_create_topic == true ): ?>
  <a 
    class="btn btn-info" 
    href="index.php?controller=topic&action=create&board_id=<?=$board->id?>"
    style="float: right;"
  >Create Topic</a>
  <?php endif; ?>
  </h2>
  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Name</th> 
          <th>Comment Count</th> 
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $board->getTopics() as $topic ): ?>
          <tr>
            <td>
                <a href="index.php?controller=topic&action=view&id=<?=$topic->id?>"><?=$topic->title?></a>
                <p class="small"><?=substr($topic->body,0,25)?>...</p>
            </td>
            <td><?=count($topic->getComments())?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <div>Board Not Found</div>
<?php endif; ?>
