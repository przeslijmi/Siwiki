<?php declare(strict_types=1);

use Przeslijmi\Siwiki\Siwiki;
use Przeslijmi\Siwiki\PhpDocGenerator;

// Call bootstrap.
require(dirname(dirname(__FILE__)) . '/bootstrap.php');

$json = new PhpDocGenerator();
$json->setDstUri('docs/');
$json->addApp('przeslijmi/siwiki', 'src/')->setComposerJson('composer.json');
$json->create();

// Start app.
$siwiki = new Siwiki();
$siwiki->setSrcUri('docs/');
$siwiki->setDstUri('../siwiki.wiki/');
$siwiki->setInclDoc(true);
$siwiki->craft();
