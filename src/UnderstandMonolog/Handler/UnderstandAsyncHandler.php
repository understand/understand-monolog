<?php namespace UnderstandMonolog\Handler;

class UnderstandAsyncHandler extends UnderstandBaseHandler
{

    /**
     * Send data to storage
     *
     * @param string $requestData
     * @return void
     */
    protected function send($requestData)
    {
        $parts = [
            'curl',
            '-X POST',
            '--cacert',
            $this->sslBundlePath,
            '-d',
            escapeshellarg($requestData),
            $this->getEndpoint(),
            '> /dev/null 2>&1 &'
        ];

        $cmd = implode(' ', $parts);

        exec($cmd);
    }
}