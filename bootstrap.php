<?php
require 'vendor/autoload.php';

use Cartrack\Config\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
