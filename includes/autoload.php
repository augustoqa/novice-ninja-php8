<?php

function autoloader($className)
{
	include __DIR__ . "/../classes/{$className}.php";
}

spl_autoload_register('autoloader');