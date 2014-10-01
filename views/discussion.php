<?php include '_head.php'; ?>

<div class="page-header">
  <h1><?php echo $thread['title']; ?></h1>
</div>

<form class="panel panel-default panel-body" method="post">
  <fieldset>
    <legend>Join this Discussion</legend>
    <div class="form-group">
      <label>Your Name</label>
      <input type="text" class="form-control" name="author_name" placeholder="John Doe" required>
    </div>
    <div class="form-group">
      <label>Your E-Mail</label>
      <input type="email" class="form-control" name="author_email" placeholder="john@acme.com" required>
    </div>
    <div class="form-group">
      <label>Message</label>
      <textarea rows="4" class="form-control" name="message" placeholder="It was the best of times, it was the worst of times." required></textarea>
    </div>
    <button type="submit" class="btn btn-default">Post</button>
  </fieldset>
</form>

<?php foreach ($posts as $post): ?>
<article class="panel panel-default">
  <div class="panel-body <?php if ( ! empty($post['isFlagged'])) echo 'bg-danger'; ?>">
    <?php echo $post['message']; ?>
    <form action="/discussion/<?php echo $thread['id']; ?>/moderate/<?php echo $post['id']; ?>" method="post">
      <button type="submit" class="btn btn-default">Flag this post</button>
    </form>
  </div>

  <footer class="panel-footer">
    <img src="<?php echo $post['author']['avatar']['permalink']; ?>" alt="" width="16" height="16">
  <?php if ( ! empty($post['author']['profileUrl'])): ?>
    <a href="<?php echo $post['author']['profileUrl']; ?>" target="_blank"><?php echo $post['author']['name']; ?></a>
  <?php elseif ( ! empty($post['author']['url'])): ?>
    <a href="<?php echo $post['author']['url']; ?>" target="_blank"><?php echo $post['author']['name']; ?></a>
  <?php else: ?>
    <b><?php echo $post['author']['name']; ?></b>
  <?php endif; ?>
    <small>on <?php echo date('l, F jS Y \a\t h:ia', strtotime($post['createdAt'])); ?></small>
  </footer>
</article>
<?php endforeach; ?>

<?php include '_foot.php'; ?>