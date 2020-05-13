<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/System/autoload.php';

System\App::run();