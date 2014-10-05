<?php
require dirname(__FILE__).'/system/ham.php';

$streak = new streak('index');
$cache1 = streak::create_cache('default', True);
$streak->route('/', function($streak) {
	require dirname(__FILE__).'/system/markdown.php';
	require dirname(__FILE__).'/system/config.php';
	$posts = glob($streak_config["streak_post_directory"].'*.'.$streak_config["streak_post_extension"]);
	usort($posts, create_function('$a,$b', 'return -(filectime($a) - filectime($b));'));
	$posts_detail = array();
	$ie = 1;
	foreach($posts as $post) {
		if($ie>3) {
			break;
		}
		$date = substr(basename($post, '.'.$streak_config['streak_post_extension']), 0,10);
		$slug = substr(basename($post, '.'.$streak_config['streak_post_extension']), 11);

		$temp = explode("\n", file_get_contents($post));
		$title = substr($temp[0],0, 1)==='!'?substr($temp[0],2):substr($temp[0],1);
		if(substr($temp[0],0, 1)==='!') {
			continue;
		}
		

		$post_content = substr(rtrim(strip_tags(Markdown(implode("\n", array_slice(explode("\n", file_get_contents($post)), 1))))),0,$streak_config['streak_post_preview_length']-3);
		array_push($posts_detail, array(
			"date" => $date,
			"slug" => $slug,
			"title" => $title,
			"post_content" => $post_content,
			"link" => $streak_config["streak_url"].$streak_config["streak_url_prefix"].str_replace("-","/",$date).'/'.$slug,
		));
		$ie++;
	}
	return $streak->render('home.html', array(
		"streak_blog_author" => $streak_config["streak_blog_author"],
		"streak_blog_name" => $streak_config["streak_blog_name"],
		"streak_blog_description" => $streak_config["streak_blog_description"],
		"streak_url" => $streak_config["streak_url"],
		"streak_url_prefix" => $streak_config["streak_url_prefix"],
		"streak_disqus_id" => $streak_config["streak_disqus_id"],
		"posts" => $posts_detail,
	));
});
$streak->route('/work', function($streak) {
	require dirname(__FILE__).'/system/markdown.php';
	require dirname(__FILE__).'/system/config.php';
	$posts = glob($streak_config["streak_post_directory"].'*.'.$streak_config["streak_post_extension"]);
	usort($posts, create_function('$a,$b', 'return -(filectime($a) - filectime($b));'));
	$posts_detail = array();
	$ie = 1;
	foreach($posts as $post) {
		if($ie>3) {
			break;
		}
		$date = substr(basename($post, '.'.$streak_config['streak_post_extension']), 0,10);
		$slug = substr(basename($post, '.'.$streak_config['streak_post_extension']), 11);

		$temp = explode("\n", file_get_contents($post));
		$title = substr($temp[0],0, 1)==='!'?substr($temp[0],2):substr($temp[0],1);
		if(substr($temp[0],0, 1)==='!') {
			continue;
		}
		

		$post_content = substr(rtrim(strip_tags(Markdown(implode("\n", array_slice(explode("\n", file_get_contents($post)), 1))))),0,$streak_config['streak_post_preview_length']-3);
		array_push($posts_detail, array(
			"date" => $date,
			"slug" => $slug,
			"title" => $title,
			"post_content" => $post_content,
			"link" => $streak_config["streak_url"].$streak_config["streak_url_prefix"].str_replace("-","/",$date).'/'.$slug,
		));
		$ie++;
	}
	return $streak->render('work.html', array(
		"streak_blog_author" => $streak_config["streak_blog_author"],
		"streak_blog_name" => $streak_config["streak_blog_name"],
		"streak_blog_description" => $streak_config["streak_blog_description"],
		"streak_url" => $streak_config["streak_url"],
		"streak_url_prefix" => $streak_config["streak_url_prefix"],
		"streak_disqus_id" => $streak_config["streak_disqus_id"],
		"posts" => $posts_detail,
	));
});
$streak->route('/<int>/<int>/<int>/<string>', function($streak, $year,$month,$day,$slug) {
	require dirname(__FILE__).'/system/markdown.php';
	require dirname(__FILE__).'/system/config.php';
	$contents = @file_get_contents($streak_config["streak_post_directory"].$year.'-'.$month.'-'.$day.'-'.$slug.'.'.$streak_config["streak_post_extension"]);
	if($contents === FALSE) {
		return $streak->render('404.html', array(
			"streak_blog_name" => $streak_config["streak_blog_name"],
			"streak_blog_description" => $streak_config["streak_blog_description"],
			"streak_url" => $streak_config["streak_url"],
			"streak_url_prefix" => $streak_config["streak_url_prefix"],
		));
	}
	else {
		$date = $year.'-'.$month.'-'.$day;
		$temp = explode("\n", $contents);
		$title = substr($temp[0],0, 1)==='!'?substr($temp[0],2):substr($temp[0],1);
		$post_content = $streak_config["streak_enable_markdown"]?Markdown(implode("\n", array_slice(explode("\n", $contents), 1))):implode("\n", array_slice(explode("\n", $contents), 1));
		return $streak->render('post.html', array(
			"streak_blog_author" => $streak_config["streak_blog_author"],
			"streak_blog_name" => $streak_config["streak_blog_name"],
			"streak_blog_description" => $streak_config["streak_blog_description"],
			"streak_url" => $streak_config["streak_url"],
			"streak_url_prefix" => $streak_config["streak_url_prefix"],
			"streak_disqus_id" => $streak_config["streak_disqus_id"],
			"date" => $date,
			"title" => $title,
			"post_content" => $post_content,
		));
	}
});
$streak->route('/<int>/<int>/<int>/', function($streak, $year,$month,$day) {
	require dirname(__FILE__).'/system/markdown.php';
	require dirname(__FILE__).'/system/config.php';
	$posts = glob($streak_config["streak_post_directory"].'*.'.$streak_config["streak_post_extension"]);
	usort($posts, create_function('$a,$b', 'return -(filectime($a) - filectime($b));'));
	$posts_detail = array();
	foreach($posts as $post) {
		$date = substr(basename($post, '.'.$streak_config['streak_post_extension']), 0,10);
		if($date===($year.'-'.$month.'-'.$day)) {
			$slug = substr(basename($post, '.'.$streak_config['streak_post_extension']), 11);
			$temp = explode("\n", file_get_contents($post));
			$title = substr($temp[0],0, 1)==='!'?substr($temp[0],2):substr($temp[0],1);
			$post_content = substr(rtrim(strip_tags(Markdown(implode("\n", array_slice(explode("\n", file_get_contents($post)), 1))))),0,$streak_config['streak_post_preview_length']-3);
			array_push($posts_detail, array(
				"date" => $date,
				"slug" => $slug,
				"title" => $title,
				"post_content" => $post_content,
				"link" => $streak_config["streak_url"].$streak_config["streak_url_prefix"].str_replace("-","/",$date).'/'.$slug,
			));
		}
	}
	if(sizeof($posts_detail)==0) {
		return $streak->render('404.html', array(
			"streak_blog_name" => $streak_config["streak_blog_name"],
			"streak_blog_description" => $streak_config["streak_blog_description"],
			"streak_url" => $streak_config["streak_url"],
			"streak_url_prefix" => $streak_config["streak_url_prefix"],
		));
	}
	return $streak->render('home.html', array(
		"streak_blog_author" => $streak_config["streak_blog_author"],
		"streak_blog_name" => $streak_config["streak_blog_name"],
		"streak_blog_description" => $streak_config["streak_blog_description"],
		"streak_url" => $streak_config["streak_url"],
		"streak_url_prefix" => $streak_config["streak_url_prefix"],
		"streak_disqus_id" => $streak_config["streak_disqus_id"],
		"posts" => $posts_detail,
	));
});
$streak->route('/<int>/<int>/', function($streak, $year,$month) {
	require dirname(__FILE__).'/system/markdown.php';
	require dirname(__FILE__).'/system/config.php';
	$posts = glob($streak_config["streak_post_directory"].'*.'.$streak_config["streak_post_extension"]);
	usort($posts, create_function('$a,$b', 'return -(filectime($a) - filectime($b));'));
	$posts_detail = array();
	foreach($posts as $post) {
		$date = substr(basename($post, '.'.$streak_config['streak_post_extension']), 0,7);
		$actualdate = substr(basename($post, '.'.$streak_config['streak_post_extension']), 0,10);
		if($date===($year.'-'.$month)) {
			$slug = substr(basename($post, '.'.$streak_config['streak_post_extension']), 11);
			$temp = explode("\n", file_get_contents($post));
			$title = substr($temp[0],0, 1)==='!'?substr($temp[0],2):substr($temp[0],1);
			$post_content = substr(rtrim(strip_tags(Markdown(implode("\n", array_slice(explode("\n", file_get_contents($post)), 1))))),0,$streak_config['streak_post_preview_length']-3);
			array_push($posts_detail, array(
				"date" => $actualdate,
				"slug" => $slug,
				"title" => $title,
				"post_content" => $post_content,
				"link" => $streak_config["streak_url"].$streak_config["streak_url_prefix"].str_replace("-","/",$actualdate).'/'.$slug,
			));
		}
	}
	if(sizeof($posts_detail)==0) {
		return $streak->render('404.html', array(
			"streak_blog_name" => $streak_config["streak_blog_name"],
			"streak_blog_description" => $streak_config["streak_blog_description"],
			"streak_url" => $streak_config["streak_url"],
			"streak_url_prefix" => $streak_config["streak_url_prefix"],
		));
	}
	return $streak->render('home.html', array(
		"streak_blog_author" => $streak_config["streak_blog_author"],
		"streak_blog_name" => $streak_config["streak_blog_name"],
		"streak_blog_description" => $streak_config["streak_blog_description"],
		"streak_url" => $streak_config["streak_url"],
		"streak_url_prefix" => $streak_config["streak_url_prefix"],
		"streak_disqus_id" => $streak_config["streak_disqus_id"],
		"posts" => $posts_detail,
	));
});
$streak->route('/<int>/', function($streak, $year) {
	require dirname(__FILE__).'/system/markdown.php';
	require dirname(__FILE__).'/system/config.php';
	$posts = glob($streak_config["streak_post_directory"].'*.'.$streak_config["streak_post_extension"]);
	usort($posts, create_function('$a,$b', 'return -(filectime($a) - filectime($b));'));
	$posts_detail = array();
	foreach($posts as $post) {
		$date = substr(basename($post, '.'.$streak_config['streak_post_extension']), 0,4);
		$actualdate = substr(basename($post, '.'.$streak_config['streak_post_extension']), 0,10);
		if($date===($year)) {
			$slug = substr(basename($post, '.'.$streak_config['streak_post_extension']), 11);
			$temp = explode("\n", file_get_contents($post));
			$title = substr($temp[0],0, 1)==='!'?substr($temp[0],2):substr($temp[0],1);
			$post_content = substr(rtrim(strip_tags(Markdown(implode("\n", array_slice(explode("\n", file_get_contents($post)), 1))))),0,$streak_config['streak_post_preview_length']-3);
			array_push($posts_detail, array(
				"date" => $actualdate,
				"slug" => $slug,
				"title" => $title,
				"post_content" => $post_content,
				"link" => $streak_config["streak_url"].$streak_config["streak_url_prefix"].str_replace("-","/",$actualdate).'/'.$slug,
			));
		}
	}
	if(sizeof($posts_detail)==0) {
		return $streak->render('404.html', array(
			"streak_blog_name" => $streak_config["streak_blog_name"],
			"streak_blog_description" => $streak_config["streak_blog_description"],
			"streak_url" => $streak_config["streak_url"],
			"streak_url_prefix" => $streak_config["streak_url_prefix"],
		));
	}
	return $streak->render('home.html', array(
		"streak_blog_author" => $streak_config["streak_blog_author"],
		"streak_blog_name" => $streak_config["streak_blog_name"],
		"streak_blog_description" => $streak_config["streak_blog_description"],
		"streak_url" => $streak_config["streak_url"],
		"streak_url_prefix" => $streak_config["streak_url_prefix"],
		"streak_disqus_id" => $streak_config["streak_disqus_id"],
		"posts" => $posts_detail,
	));
});
//add custom routes for your own pages
$streak->run();

?>
