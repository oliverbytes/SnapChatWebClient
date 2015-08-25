<?php

function timeSince ($time)
{
    $time = time() - $time; // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) 
    {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':' ago');
    }
}

$datetime = "2013-12-30 21:39:50";

$new = date("Y-m-d", strtotime($datetime));

echo "NEW: ".timeSince(strtotime($datetime));

?>