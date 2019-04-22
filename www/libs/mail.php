<?php

function send_mail($to, $subject, $message)
{
    global $globals;

    if (!check_email($to)) {
        return;
    }

    if (empty($globals['email_domain'])) {
        $domain = get_server_name();
    } else {
        $domain = $globals['email_domain'];
    }

    $subject = mb_encode_mimeheader("$domain: $subject", "UTF-8", "B", "\n");
    $message = wordwrap($message, 70);

    $headers = 'Content-Type: text/plain; charset="utf-8"'."\n" .
        'From: '._('оповещение ').' '.$domain.' <'._('admin')."@$domain>\n".
        'Reply-To: '._('admin')."@$domain\n".
        'X-Mailer: sugata.ru' . "\n".
        'MIME-Version: 1.0' . "\n";

    return mail($to, $subject, $message, $headers);
}

function send_mail_list($subject, $message)
{
    global $globals;

    if (empty($globals['adm_list_email']) || empty($globals['adm_list_email_from'])) {
        return;
    }

    $headers = 'Content-Type: text/plain; charset="utf-8"'."\n" .
        'From: '.$globals['adm_list_email_from']."\n".
        'Reply-To: '.$globals['adm_list_email']."\n".
        'X-Mailer: sugata.ru' . "\n".
        'MIME-Version: 1.0' . "\n";

    return mail($globals['adm_list_email'], $subject, $message, $headers);
}

function send_recover_mail($user, $echo = true)
{
    global $site_key, $globals;

    if (!check_email($user->email)) {
        return;
    }

    $now = time();

    if (empty($globals['email_domain'])) {
        $domain = get_server_name();
    } else {
        $domain = $globals['email_domain'];
    }

    $key = md5($user->id.$user->pass.$now.$site_key.get_server_name());
    $url = $globals['scheme'].'//'.get_server_name().$globals['base_url'].'profile?login='.$user->username.'&t='.$now.'&k='.$key;

    $to = $user->email;

    $subject = _('Восстановление или проверка пароля '). get_server_name();
    $subject = mb_encode_mimeheader($subject, "UTF-8", "B", "\n");

    $message = $to . ': '._('чтобы получить доступ без ключа, подключитесь к следующему адресу менее чем за 15 минут:') . "\n\n$url\n\n";
    $message .= _('По истечении этого времени вы можете повторно запросить доступ в: ') . "\nhttp://".get_server_name().$globals['base_url']."login?op=recover\n\n";
    $message .= _('После того, как в вашем профиле, вы можете изменить ключ доступа.') . "\n" . "\n";
    $message .= "\n\n". _('Это сообщение было отправлено по адресу: ') . $globals['user_ip'] . "\n\n";
    $message .= "-- \n  " . _('Робот системы Sugata');
    $message = wordwrap($message, 70);

    $headers = 'Content-Type: text/plain; charset="utf-8"'."\n" .
        'From: '._('Оповещение от ').' '.$domain.' <'._('admin')."@$domain>\n".
        'Reply-To: '._('admin')."@$domain\n".
        'X-Mailer: sugata.ru' . "\n".
        'MIME-Version: 1.0' . "\n";

    mail($to, $subject, $message, $headers);

    if ($echo) {
        echo '<p><strong>'._('Письмо отправлено, посмотрите в своем почтовом ящике, там есть инструкции. Посмотри также в папке спам.').'</strong></p>';
    }

    return true;
}
