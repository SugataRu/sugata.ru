<?php

class Time
{
    protected function strftime($format, $date)
    {
        return strftime($format, is_numeric($date) ? $date : strtotime($date));
    }

    public static function year($date)
    {
        return (int) static::strftime('%Y', $date);
    }

    public static function yearShort($date)
    {
        return (int) static::strftime('%g', $date);
    }

    public static function month($date)
    {
        return [
            1 => _('январь'),
            2 => _('февраль'),
            3 => _('март'),
            4 => _('апрель'),
            5 => _('май'),
            6 => _('июнь'),
            7 => _('июль'),
            8 => _('август'),
            9 => _('сентябрь'),
            10 => _('октябрь'),
            11 => _('ноябрь'),
            12 => _('декабрь'),
        ][(int) static::strftime('%m', $date)];
    }

    public static function monthSort($date)
    {
        return substr(static::month($date), 0, 3);
    }

    public static function day($date)
    {
        return [
            1 => _('понедельник'),
            2 => _('вторник'),
            3 => _('среда'),
            4 => _('четверг'),
            5 => _('пятница'),
            6 => _('суббота'),
            7 => _('воскресенье'),
        ][(int) static::strftime('%u', $date)];
    }

    public static function dayShort($date)
    {
        return substr(static::day($date), 0, 3);
    }

    public static function hour($date)
    {
        return static::strftime('%R', $date);
    }

    public static function dayMonthSortHour($date)
    {
		return  static::hour($date).'ч.';
		
       // return static::strftime('%e', $date).'/'.static::monthSort($date).' - '.static::hour($date).'ч.';
    }

    public static function diff($from, $now = 0)
    {
        global $globals;

        if (!preg_match('/^[0-9]+$/', $from)) {
            $from = strtotime($from);
        }

        if (empty($now)) {
            $now = $globals['now'];
        }

        $diff = $now - $from;
        $days = intval($diff / 86400);

        $diff = $diff % 86400;
        $hours = intval($diff / 3600);

        $diff = $diff % 3600;
        $minutes = intval($diff / 60);

        $secs = $diff % 60;

        if ($days > 1) {
            $txt = $days.' '._('дней');
        } elseif ($days === 1) {
            $txt = $days.' '._('день');
        } else {
            $txt = '';
        }

        if ($hours > 1) {
            $txt .= ' '.$hours.' '._('часов');
        } elseif ($hours === 1) {
            $txt .= ' '.$hours.' '._('час');
        }

        if ($minutes > 1) {
            $txt .= ' '.$minutes.' '._('минут');
        } elseif ($minutes === 1) {
            $txt .= ' '.$minutes.' '._('минута');
        }

        if ($txt) {
            return trim($txt);
        }

        if ($secs < 5) {
            return _('ничего');
        }

        return $secs.' '._('секунд');
    }

    public static function leftTo($date)
    {
        $interval = date_create('now')->diff(date_create($date));

        if (($interval->d >= 3) || (($interval->d > 1) && ($interval->h === 0))) {
            return sprintf(_('Осталось %s дней'), $interval->d);
        }

        if ($interval->d > 1) {
            return sprintf(_('Осталось %s дней %s часов'), $interval->d, $interval->h);
        }

        if (($interval->d === 1) && ($interval->h === 0)) {
            return _('Остался 1 день');
        }

        if ($interval->d === 1) {
            return sprintf(_('Остался 1 день и %s часов'), $interval->h);
        }

        if (($interval->h > 1) && ($interval->i === 0)) {
            return sprintf(_('Осталось %s часов'), $interval->h);
        }

        if ($interval->h > 1) {
            return sprintf(_('Осталось %s час %s минут'), $interval->h, $interval->i);
        }

        if (($interval->h === 1) && ($interval->i === 0)) {
            return _('Остался 1 час');
        }

        if ($interval->h === 1) {
            return sprintf(_('Остался 1 час и %s минут'), $interval->i);
        }

        if ($interval->i > 1) {
            return sprintf(_('Осталось %s минут'), $interval->i);
        }

        if ($interval->i === 1) {
            return _('Осталась 1 минута');
        }

        return sprintf(_('Осталось %s секунд'), $interval->s);
    }
}
