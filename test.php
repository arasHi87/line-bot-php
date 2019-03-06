<?php
$text = file_get_contents('set.json');
$set = json_decode($text);
echo "$set->hello";