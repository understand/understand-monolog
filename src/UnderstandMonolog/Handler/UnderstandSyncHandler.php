<?php namespace UnderstandMonolog\Handler;

class UnderstandSyncHandler extends UnderstandBaseHandler
{

    /**
     * Send data to storage
     *
     * @param mixed $requestData
     * @return type
     */
    protected function send($requestData)
    {
        $endpoint = $this->getEndpoint();
        $ch = curl_init($endpoint);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        if ($this->sslBundlePath)
        {
            curl_setopt($ch, CURLOPT_CAINFO, $this->sslBundlePath);
        }

        curl_setopt($ch, CURLOPT_USERAGENT, 'Understand Monolog');

        $response = curl_exec($ch);

        if ($response === false)
        {
            $this->lastError = curl_error($ch);
        }
        else
        {
            $this->lastError = null;
        }

        curl_close($ch);

        return $response;
    }
}