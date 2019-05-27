<?php
// The source code packaged with this file is Free Software, Copyright (C) 2019 by sugata.ru 

global $globals;

function design_exists($user, $type = 'user')
{
    global $db;
    $rez = $db->get_results("SELECT user_id,user_img_feed FROM users WHERE user_id=$user");
    foreach ($rez as $fimg) {
       $img_feed = $fimg->user_img_feed;
    }
    return $img_feed;
}

function design_add($user, $type = 'user')
{
    global $db, $globals;
    $type = $db->escape($type);
    return $db->query("UPDATE users set user_img_feed='1' where user_id=$user");
}

function design_delete($user, $type = 'user')
{
    global $db;
    $type = $db->escape($type);
    return $db->query("UPDATE users set user_img_feed='0' where user_id=$user");
	
}

function design_add_delete($user, $type = 'user')
{
    global $globals;
    if (design_exists($user, $type)) {
        design_delete($user, $type);
        return 0;
    } else {
        design_add($user, $type);
        return 1;
		
    }
}