<?php

class AdminUser
{
    public static function levels()
    {
        return ['admin', 'god'];
    }

    public static function sectionsDefault()
    {
        return [
            'admin_logs', 'comment_reports', 'strikes', 'hostname', 'punished_hostname',
            'email', 'ip', 'words', 'noaccess', 'mafia'
        ];
    }

    public static function allowed($admin_id, $section)
    {
        global $db;

        return (bool)$db->get_var('
            SELECT COUNT(*)
            FROM `admin_users`, `admin_sections`
            WHERE (
                `admin_users`.`admin_id` = "'.(int)$admin_id.'"
                AND `admin_sections`.`name` = "'.$db->escape($section).'"
                AND `admin_users`.`section_id` = `admin_sections`.`id`
            )
            LIMIT 1;
        ');
    }

    public static function sectionsByAdminId($admin_id)
    {
        global $db;

        return $db->get_col('
            SELECT `admin_sections`.`name`
            FROM `admin_sections`, `admin_users`
            WHERE (
                `admin_users`.`admin_id` = "'.(int)$admin_id.'"
                AND `admin_sections`.`id` = `admin_users`.`section_id`
            );
        ');
    }

    public static function sections()
    {
        global $db;

        return $db->get_col('
            SELECT `name`
            FROM `admin_sections`
            ORDER BY `name` ASC;
        ');
    }

    public static function sectionsJoindedUserId($admin_id)
    {
        global $db;

        return $db->get_results('
            SELECT `admin_sections`.`id`, `admin_sections`.`name`, `admin_users`.`admin_id`
            FROM `admin_sections`
            LEFT JOIN `admin_users` ON (
                `admin_users`.`section_id` = `admin_sections`.`id`
                AND `admin_users`.`admin_id` = "'.(int)$admin_id.'"
            )
            ORDER BY `admin_sections`.`name` ASC;
        ');
    }

    public static function listing()
    {
        global $db;

        $list = $db->get_results('
            SELECT `users`.`user_id`, `users`.`user_login`, `users`.`user_level`,
                GROUP_CONCAT(`admin_sections`.`name` ORDER BY `admin_sections`.`name`) AS `sections`
            FROM `users`, `admin_users`
            JOIN `admin_sections` ON (`admin_sections`.`id` = `admin_users`.`section_id`)
            WHERE `users`.`user_id` = `admin_users`.`admin_id`
            GROUP BY `users`.`user_login`
            ORDER BY `users`.`user_login` ASC;
        ');

        foreach ($list as $row) {
            $row->sections = explode(',', $row->sections);
        }

        return $list;
    }

    public static function changeLevel($user, $previous, $new)
    {
        global $db;

        $levels = static::levels();
        $previous = in_array($previous, $levels);
        $new = in_array($new, $levels);

        if (!$user->id || (!$previous && !$new) || ($previous && $new)) {
            return;
        }

        if ($previous) {
            return $db->query('
                DELETE FROM `admin_users`
                WHERE `admin_id` = "'.(int)$user->id.'";
            ');
        }

        if (!$new) {
            return;
        }

        $db->query('
            INSERT INTO `admin_users`
            (`admin_id`, `section_id`)
            (
                SELECT "'.(int)$user->id.'", `id`
                FROM `admin_sections`
                WHERE `name` IN ("'.implode('", "', static::sectionsDefault()).'")
            );
        ');
    }

    public static function relateAdminWithSectionIds($admin_id, array $ids)
    {
        global $db;

        $admin_id = (int)$admin_id;

        $db->query('
            DELETE FROM `admin_users`
            WHERE `admin_id` = "'.$admin_id.'";
        ');

        $db->query('
            INSERT INTO `admin_users`
            (`admin_id`, `section_id`)
            (
                SELECT "'.$admin_id.'", `id`
                FROM `admin_sections`
                WHERE `id` IN ('.DbHelper::implodedIds($ids).')
            );
        ');
    }
}
