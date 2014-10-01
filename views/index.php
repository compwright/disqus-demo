<?php include '_head.php'; ?>

<div class="page-header">
  <h1>Discussions</h1>
</div>

<form class="panel panel-default panel-body" method="post">
  <fieldset>
    <legend>Start a Discussion</legend>
    <div class="form-group">
      <input type="text" class="form-control" name="title" placeholder="Discussion Name" required>
    </div>
    <button type="submit" class="btn btn-default">Add</button>
  </fieldset>
</form>

<nav class="panel panel-default">
  <ul class="nav nav-stacked">
  <?php foreach ($threads as $thread): ?>
    <li>
      <a href="/discussion/<?php echo $thread['id']; ?>"><?php echo $thread['title']; ?></a>
    </li>
  <?php endforeach; ?>
  </ul>
</nav>

<?php include '_foot.php'; ?>