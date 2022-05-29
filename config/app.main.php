<?php

require "app.configs.php";

use \App\Configs;

$app = new \App\Main();

$app->Cache = new \App\Data\Cache($this, CACHE_HOST, CACHE_PASSWORD, CACHE_PORT);
