<?php

$tags = array(
	array(20, 'star trek'),
	array(10, 'stern'),
	array(1, 'star wars'),
	array(10, 'conan')
);

$config = array( // these values are all optional
	'min_font_em' => 1,
	'max_font_em' => 3,
	'base_url' => '/tag/'
);

require_once('../classes/Tags.php');
$tagger = new Tags($config);
$tags_html = $tagger->cloud($tags);

?>

<?= $tags_html ?>

