<?php

namespace Convoworks;

if (!\class_exists('Convoworks\\PhpParser\\Autoloader')) {
    require __DIR__ . '/PhpParser/Autoloader.php';
}
PhpParser\Autoloader::register();
