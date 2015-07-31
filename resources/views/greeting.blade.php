<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		@if (isset($metaDescription))
			<meta name="description" content="{{ $metaDescription }}">
		@endif
		<meta name="keywords" content="hashtagsnow, hashtags, instagram, facebook, twitter, socialmedia, tags4likes, tagsfourlikes, followers, instafamous">
		<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, target-densitydpi=device-dpi" />
		<title>#hashtagsnow</title>
		<link href='http://fonts.googleapis.com/css?family=Lora|Oswald:400,300' rel='stylesheet' type='text/css'>
		<style>
			<?= file_get_contents('css/screen.css') ?>
		</style>
		<script type="text/javascript" src="/js/hashtagsnow.min.js" defer></script>
	</head>
	<body>
		<header class="clear top">
			<div class="name clear">
				<h1><a href="/">#hashtagsnow</a></h1>
			</div>
		</header>
		@foreach ($twitter as $t)
			<div class="outer">
				<div class="inner">
					<h1>#{{ str_replace('#', '', $t->name) }}</h1>
					<h2>Source: Twitter</h2>
				</div>
			</div>
		@endforeach
		<footer class="clear">
			<div class="outer short no-border">
				<div class="inner clear">
					<div class="copyright">
						&copy; {{ date( 'Y' ) }} Alexander Hripak | <a href="/#FIXME">API Docs</a>
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>
