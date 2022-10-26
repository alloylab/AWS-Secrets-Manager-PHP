<?php

use AWSM\Load;
use AWSM\File;

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once('../vendor/autoload.php');
require_once('../File.php');
require_once('../Load.php');
require_once('../AWS_Secrets.php');
require_once('../AWS_DB_IAM.php');

try {
    $secrets_load = new Load('/var/secrets.json', getenv('AWS_DEFAULT_REGION'), getenv('AWS_ACCESS_ID'), getenv('AWS_SECRET_KEY'));

    $secrets_load->secrets(array(getenv('SECRET')));
    $secrets_load->db(getenv('DB_HOST'), getenv('DB_USERNAME'));

    File::st_retrieve('/var/secrets.json', 'db_token' . getenv('DB_HOST'));
} catch (Exception $e) {
    return 'Exception: ' .  $e->getMessage();
}
