<?php

/*****
// banners and credits funcions: FUNCTIONS TO ADAPT TO YOUR CONTRACTED ADS AND CREDITS
*****/

function banner_enabled($mobile = false)
{
    global $globals;

    return ($globals['ads'] && (!$globals['mobile'] || $mobile));
}

function do_banner($template, $mobile = false)
{
    if (banner_enabled($mobile)) {
        Haanga::Safe_Load($template.'.html');
    }
}

function do_banner_private($template, $mobile = false)
{
    do_banner('private/'.$template, $mobile);
}

function do_banner_inc($template, $mobile = false)
{
    $file = __DIR__.'/ads/'.$template.'.inc';

    if (banner_enabled($mobile) && is_file($file)) {
        include $file;
    }
}

function do_banner_top()
{
    global $globals;

    if ($globals['external_ads'] && $globals['ads']) {
        Haanga::Safe_Load('private/top.html');
    }
}

function do_banner_top_mobile()
{
    do_banner_inc('mobile-01', true);
}

function do_banner_right()
{
    do_banner_private('ad-right');
}

function do_banner_promotions()
{
    do_banner_private('promotions');
}

function do_banner_top_news()
{
    do_banner_private('top-news', true);
}

function do_banner_story()
{
    do_banner_private('ad-middle', true);
}

function do_legal($legal_name, $target = '', $show_abuse = true)
{
    global $globals;

    // IMPORTANT: legal note only for our servers, CHANGE IT!!
    if ($globals['is_meneame']) {
        echo '<a href="'.$globals['legal'].'" '.$target.'>'.$legal_name.'</a>';
    } else {
        echo 'legal conditions link here';
    }
    // IMPORTANT: read above
}

function do_credits_mobile()
{
    global $dblang, $globals;

    echo '<div id="footthingy">';
    echo '<a href="https://sugata.ru" title="sugata.ru"><img src="'.$globals['base_static'].'img/meneito.png" alt="Sugata"/></a>';

    echo '</div>'."\n";
}
