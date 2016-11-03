<?php
/**
 * Created by PhpStorm.
 * User: leonid
 * Date: 02.11.16
 * Time: 15:21
 */

namespace App\Http\Middleware;

use App\Models\Message;
use Symfony\Component\Finder\Comparator\DateComparator;
use \DateTime;
use \DateInterval;

class MessageHelper
{
    public static function isMessageExpired(Message $messageRow)
    {
        $isExpired = false;
        $currentTime = new DateTime();
        $date = new DateTime($messageRow->created_at);
        $interval = new DateInterval('PT' . $messageRow->time_to_live . 'S');
        $date->add($interval);
        $iv = $currentTime->diff($date);
        if ($date->getTimestamp() <= $currentTime->getTimestamp()) {
            $isExpired = true;
            $type = 'danger';
            $message = 'WARNING THIS Message is unavailable because of timeout expired';
        } else {
            $type = 'warning';
            $message = "WARNING THIS Message will be unavailable at {$date->format('Y-m-d H:i:s')} in {$iv->format('%h:%i:%s')}";
        }
        \Session::flash('flash_message_important', true);
        \Session::flash('flash_message', [
                'type' => $type,
                'message' => $message
            ]
        );

        return $isExpired;
    }
}