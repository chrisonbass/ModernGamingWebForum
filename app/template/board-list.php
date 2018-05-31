<?php
?>
<div class="jumbotron">
  <h1 class="display-4">Discussion Boards</h1>
  <p class="lead">
    This is where you can see the different discussion boards available for you to add a new topic!
  </p>
</div>
<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Name</th> 
        <th>Topic Count</th> 
        <th>Last Topic</th> 
      </tr>
    </thead>
    <tbody>
      <?php foreach ( $list as $board ): ?>
      <?php $latest_topic = $board->getLatestTopic(); ?>
        <tr>
          <td>
              <a href="index.php?controller=board&action=view&id=<?=$board->id?>"><?=$board->name?></a>
              <p class="small"><?=$board->description?></p>
          </td>
          <td><?=count($board->topics)?></td>
          <td>
            <?php if ( $latest_topic && $latest_topic->id ): ?>
            <a href="index.php?controller=topic&action=view&id=<?=$latest_topic->id?>"><?=$latest_topic->title?></a>
            <p class="small"><?=substr($latest_topic->body, 0, 50)?>...</p>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
