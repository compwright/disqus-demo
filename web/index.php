<?php

$app = require_once '../bootstrap.php';
$cache = require_once '../cache.php';

use compwright\Disguz\Disguz;

// List all discussion threads
$app->get('/', function() use ($app, $cache) {
	$disqus = Disguz::factory([
		'disqus.keys' => [
			'api_secret' => getenv('DISQUS_API_SECRET'),
		],
	]);
	$disqus->addSubscriber($cache);
	
	$threads = $disqus->threadsList([
		'forum' => getenv('DISQUS_FORUM'),
	]);

	$app->render('index.php', [
		'threads' => $threads['response']
	]);
});

// Start a new discussion thread
$app->post('/', function() use ($app) {
	$disqus = Disguz::factory([
		'disqus.keys' => [
			'api_key' => getenv('DISQUS_API_KEY'),
			'api_secret' => getenv('DISQUS_API_SECRET'),
			'access_token' => getenv('DISQUS_ACCESS_TOKEN'),
		],
	]);

	$result = $disqus->threadsCreate([
		'forum' => getenv('DISQUS_FORUM'),
		'title' => $app->request()->post('title'),
	]);

	$app->redirect('/');
});

// List all the posts on a thread
$app->get('/discussion/:threadId', function($thread) use ($app) {
	$disqus = Disguz::factory([
		'disqus.keys' => [
			'api_secret' => getenv('DISQUS_API_SECRET'),
		],
	]);
	
	$posts = $disqus->postsList(compact('thread'));
	$thread = $disqus->threadsDetails(compact('thread'));

	$app->render('discussion.php', [
		'posts' => $posts['response'],
		'thread' => $thread['response'],
	]);
});

// Create a new post on a thread
$app->post('/discussion/:threadId', function($thread) use ($app) {
	// Create a client and pass an array of configuration data
	$disqus = Disguz::factory([
		'disqus.keys' => [
			'api_secret' => getenv('DISQUS_API_SECRET'),
		],
	]);

	$result = $disqus->postsCreate([
		'thread' => $thread,
		'message' => $app->request()->post('message'),
		'author_name' => $app->request()->post('author_name'),
		'author_email' => $app->request()->post('author_email'),
	]);

	$app->redirect('/discussion/' . $thread);
});

// Flag an objectionable post
$app->post('/discussion/:threadId/moderate/:postId', function($thread, $post) use ($app) {
	// Create a client and pass an array of configuration data
	$disqus = Disguz::factory([
		'disqus.keys' => [
			'api_secret' => getenv('DISQUS_API_SECRET'),
		],
	]);

	$result = $disqus->postsReport(compact('post'));

	$app->redirect('/discussion/' . $thread);
});

$bench = new Ubench;
$bench->start();

$app->run();

$bench->end();
$app->render('benchmark.php', compact('bench'));
