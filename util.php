<?php
define('A_DAY', 86400);
define('DATE_FORMAT', 'j.n.Y');
define('TIME_FORMAT', 'H:i:s');
define('DATETIME_FORMAT', sprintf("%s %s", TIME_FORMAT, DATE_FORMAT));

function getNormalizedFILES()
{
    $newfiles = array();
    foreach($_FILES as $fieldname => $fieldvalue)
        foreach($fieldvalue as $paramname => $paramvalue)
            foreach((array)$paramvalue as $index => $value)
                $newfiles[$fieldname][$index][$paramname] = $value;
    return $newfiles;
}

function format_date($timestamp)
{
    return date(DATE_FORMAT, $timestamp);
}

function format_datetime($timestamp)
{
    return date(DATETIME_FORMAT, $timestamp);
}

function format_time($timestamp)
{
    return date(TIME_FORMAT, $timestamp);
}

function format_datetime_diff($a, $b, $minutes = true, $seconds = false)
{
    $dA = new DateTime("@$a");
    $dB = new DateTime("@$b");
    $diff = $dA->diff($dB, true);
    $result = "";
    if ($diff->y > 0)
        $result .= $diff->format("%y rokov ");
    if ($diff->m > 0)
        $result .= $diff->format("%m mesiacov ");
    if ($diff->d != 0)
    {
        if ($diff->d == 1)
            $result .= $diff->format("1 den ");
        else if ($diff->d < 5)
            $result .= $diff->format("%d dni ");
        else if ($diff->d > 0)
            $result .= $diff->format("%d dní ");
    }
    if ($diff->h > 0)
        $result .= $diff->format("%h:");
    $result .= $diff->format("%I");
    if ($seconds && $diff->$s > 0)
        $result .= $diff->format(" %s sekund ");
    return $result;
}
?>
