<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format a date to Thai language with Buddhist year.
     *
     * @param string|\DateTimeInterface $date
     * @param string $format Optional format string, default is 'วันl, j F Y'
     * @return string
     */
    public static function formatThaiDate($date, $format = 'วันl, j F Y'): string
    {
        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);
        $buddhistYear = $carbonDate->year + 543;

        // Set locale to Thai for translatedFormat
        $carbonDate->locale('th');

        // Format date with translated day and month names
        $formatted = $carbonDate->translatedFormat($format);

        // Replace Gregorian year with Buddhist year
        $formatted = preg_replace('/\d{4}$/', $buddhistYear, $formatted);

        return $formatted;
    }
}
