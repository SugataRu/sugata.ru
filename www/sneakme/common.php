<?php

function get_posts_menu($tab_selected, $username)
{
    global $globals, $current_user;

    if ($tab_selected != 4 && $current_user->user_id > 0) {
        $username = $current_user->user_login;
    }

    switch ($tab_selected) {
        case 2:
            $id = _('популярные');
            break;
        case 3:
            $id = _('карта');
            break;
        case 4:
            $id = $username;
            break;
        case 5:
            $id = _('ЛС');
            break;
        case 6:
            $id = _('опросы');
            break;
        default:
            $id = _('все');
            break;
    }

    $items = array();

    if (($current_user->user_id > 0) && ($tab_selected == 5)) { // Privates
        $items[] = new MenuOption(_('nuevo'), 'javascript:priv_new(0)', $id, _('nueva nota privada'), 'toggler submit_new_post');
    }

    $items[] = new MenuOption(_('все'), post_get_base_url(''), $id, _('все заметки'));
    $items[] = new MenuOption(_('популярные'), post_get_base_url('_best'), $id, _('notas populares'));

    if ($globals['google_maps_api']) {
        $items[] = new MenuOption(_('карта'), post_get_base_url('_geo'), $id, _('анимированная карта'));
    }

    if (!empty($username)) {
        $items[] = new MenuOption($username, get_user_uri($username, 'notes'), $id, $username, 'username');
    }

    if ($current_user->user_id > 0) {
        $items[] = new MenuOption(_('лс'), get_user_uri($username, 'notes_privates'), $id, _('личные сообщения'));
    }

    $items[] = new MenuOption(_('опросы'), post_get_base_url('_poll'), $id, _('заметки с опросами'));

    return $items;
}
