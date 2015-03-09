<?php namespace BaunPlugin\BlogRss;

use Baun\Plugin;

class BlogRss extends Plugin {

	public function init()
	{
		$this->events->addListener('baun.filesToPosts', [$this, 'feed']);

		$this->theme->addPath(__DIR__ . '/templates');
	}

	public function feed($event, $posts)
	{
		$posts = array_slice($posts, 0, $this->config->get('blog.posts_per_page'));
		foreach ($posts as $key => $post) {
			$posts[$key]['published'] = date('r', strtotime($post['published']));
		}

		$this->router->add('GET', '/feed', function() use ($posts) {
			header('Content-Type: application/rss+xml; charset=utf-8');
			return $this->theme->render('feed', [
				'base_url' => $this->config->get('app.base_url'),
				'posts' => $posts
			]);
		});
	}

}