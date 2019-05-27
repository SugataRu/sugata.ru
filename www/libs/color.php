<?php
// The source code packaged with this file is Free Software, Copyright (C) 2019 by sugata.ru 

global $globals;

function color_exists($user, $type = 'user')
{
    global $db;
    $rez = $db->get_results("SELECT user_id,user_color FROM users WHERE user_id=$user");
    foreach ($rez as $fimg) {
       $img_feed = $fimg->user_color;
    }
    return $img_feed;
}

function color_add($user, $type = 'user')
{
    global $db, $globals;
    $type = $db->escape($type);
    return $db->query("UPDATE users set user_color='1' where user_id=$user");
}

function color_delete($user, $type = 'user')
{
    global $db;
    $type = $db->escape($type);
    return $db->query("UPDATE users set user_color='0' where user_id=$user");
	
}

function color_add_delete($user, $type = 'user')
{
    global $globals;
    if (color_exists($user, $type)) {
        color_delete($user, $type);
        return 0;
    } else {
        color_add($user, $type);
        return 1;
		
    }
}