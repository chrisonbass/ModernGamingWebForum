<?php if ( $model && $model->id ): ?>
  <?php 
    $comments = $model->getComments();
  ?>
  <div class="jumbotron">
    <h1 class="display-4"><?=$model->title?></h1>
    <p class="lead"><?=$model->body?></p>
    <?php if ( $creator && $creator->id ): ?>
    <p><?=$creator->username?> on <?=date("D, M jS \\a\\t g:ia", strtotime($model->created_at))?> 
    <p class="like-block">
      <?php if ( $can_like_topic == true ): ?>
        <a href="javascript:void(0);" data-action="likeTopic" data-topic-id="<?=$model->id?>" title="Like this Topic">
        &#10084; <span className="like-count"><?=$like_count?></span>
        </a>
      <?php else: ?>
        &#10084; <span className="like-count"><?=$like_count?></span>
      <?php endif; ?>
    </p>
    <?php endif; ?>
  </div>
  <h2>Comments
  <?php if ( $can_delete_topic == true ): ?>
  <a 
    class="btn btn-danger" 
    href="index.php?controller=topic&action=delete&id=<?=$model->id?>"
    style="float: right;"
  >Delete Topic</a>
  <?php endif; ?>
  </h2>
  <?php if ( $user && $user->id ): ?>
  <div>
    <form action="index.php?controller=topic&action=add-comment" method="post">
      <p>Add new comment</p>
      <div class="form-group">
        <textarea class="form-control" name="body"></textarea>
      </div>
      <input type="hidden" name="topic_id" value="<?=$model->id?>" /> 
      <input type="hidden" name="user_id" value="<?=$user->id?>" /> 
      <div class="form-group text-right">
        <button type="submit" class="btn btn-primary" name="submit" value="submit">
          Add Comment
        </button>
      </div>
    </form>
  </div>
  <?php endif; ?>
  <div class="table-responsive">
    <table class="table table-hover topic-table">
      <tbody>
        <?php if ($comments && count($comments) ): ?>
        <?php foreach ( $comments as $comment ): ?>
        <?php $comm_likes = count($comment->getLikes()); ?>
          <tr>
            <td>
              <?php $cuser = $comment->getUser();?>
              <?php if ( $cuser && $cuser->id ): ?>
                <a href="index.php?controller=user&action=view&id=<?=$cuser->id?>"><?=$cuser->username?></a>
                <p class="small"><?=date("D, M jS", strtotime($comment->created_at))?></p>
              <?php endif; ?>
              <?php if ( $user && $user->id && $cuser && $cuser->id && $cuser->id == $user->id ) :?>
                <a href="index.php?controller=comment&action=delete&id=<?=$comment->id?>" class="btn btn-danger btn-sm">
                  Delete
                </a>
              <?php endif; ?>
            </td>
            <td>
              <?=$comment->body?>
              <p class="like-block">
                <?php if ( $can_like_topic == true ): ?>
                  <a href="javascript:void(0);" data-action="likeComment" data-comment-id="<?=$comment->id?>" title="Like this Comment">
                  &#10084; <span className="like-count"><?=$comm_likes?></span>
                  </a>
                <?php else: ?>
                  &#10084; <span className="like-count"><?=$comm_likes?></span>
                <?php endif; ?>
              </p>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php else: ?>
          <p>No comments yet.  Be the first to leave a comment!</p>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <div>Topic Not Found</div>
<?php endif; ?>
