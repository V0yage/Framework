<?php include __DIR__ . '/../header.php'; ?>
	<h1><?= $article->getName() ?></h1>
	<p><?= $article->getText() ?></p>
	<p><i>From: <?= $article->getAuthor()->getNickname() ?></i></p>
<?php include __DIR__ . '/../footer.php'; ?>