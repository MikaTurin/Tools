<?php

require '../Config.php';



$conf = new \App\Config(require('config.php'));


echo '<pre>';
print_r($conf->all());
echo '</pre>';