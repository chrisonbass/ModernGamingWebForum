<div class="user-view">
  <p>
    Comments: <?=$comment_count?> <br />
    Topics: <?=$topic_count?> <br />
  </p>
  <?php if ( $latest_topic && $latest_topic->id ): ?>
  <hr />
    <h3>Latest Topic</h3>
    <div class="well">
      <h4><a href="index.php?controller=topic&action=view&id=<?=$latest_topic->id?>" title="<?=$latest_topic->title?>"><?=$latest_topic->title?></a></h4>
    </div>
  <?php endif; ?>
  <?php if ( $latest_comment && $latest_comment->id ): ?>
  <hr />
  <?php $ctopic = $latest_comment->getTopic(); ?>
    <h3>Latest Comment</h3>
    <div class="well">
      <h4>In topic <em><a href="index.php?controller=topic&action=view&id=<?=$ctopic->id?>" title="<?=$ctopic->title?>"><?=$ctopic->title?></a></em></h4>
      <p><em><?=$latest_comment->body?></em></p>
    </div>
  <?php endif; ?>
</div>
