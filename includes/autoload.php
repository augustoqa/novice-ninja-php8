<?php

function autoloader($className)
{
	$fileName = str_replace('\\', '/', $className) . '.php';

	include __DIR__ . "/../{$fileName}";
}

spl_autoload_register('autoloader');