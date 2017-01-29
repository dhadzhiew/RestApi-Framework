<?php

require '../Core/App.php';

$app = \Core\App::getInstance();
$app->setConfigFolder('../config/');


$db = new \Core\DB\SimpleDB();
$sql = 'CREATE TABLE IF NOT EXISTS `news` ('
    . '`id` INT NOT NULL AUTO_INCREMENT ,'
    . '`title` VARCHAR(60) NOT NULL ,'
    . '`text` TEXT NOT NULL ,'
    . '`date` INT NOT NULL ,'
    . '`updated` INT NOT NULL ,'
    . 'PRIMARY KEY (`id`))'
    . 'ENGINE = InnoDB;';
$db->prepare($sql)
    ->execute();

echo 'Done';