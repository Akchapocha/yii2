<?php

use yii\helpers\Url;
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(['/images/favicon.ico'])]);

?>

<?= $content;?>
