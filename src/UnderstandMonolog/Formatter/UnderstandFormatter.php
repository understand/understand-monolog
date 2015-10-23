<?php namespace UnderstandMonolog\Formatter;

use Monolog\Formatter\FormatterInterface;

class UnderstandFormatter implements FormatterInterface
{

    /**
     * Format event
     *
     * @param array $record
     * @return string;
     */
    public function format(array $record)
    {
        $recordWithTimestamp = $this->convertDatetime($record);

        return json_encode($recordWithTimestamp);
    }

    /**
     * Format batch of events
     *
     * @param array $records
     * @return string
     */
    public function formatBatch(array $records)
    {
        $formatted = [];

        foreach($records as $record)
        {
            $formatted[] = $this->convertDatetime($record);
        }

        return json_encode($formatted);
    }

    /**
     * Convert datetime to _timestamp format
     *
     * @param array $record
     * @return type
     */
    protected function convertDatetime(array $record)
    {
        if (isset($record['datetime']) && $record['datetime'] instanceof \DateTime)
        {
            // U - Seconds since the Unix Epoch
            // u - Microseconds (added in PHP 5.2.2). Note that date() will always generate 000000
            // http://php.net/manual/en/function.date.php
            $record['timestamp'] = intval(round((float)$record['datetime']->format('U.u') * 1000));

            unset($record['datetime']);
        }

        return $record;
    }
}