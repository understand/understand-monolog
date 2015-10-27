<?php namespace UnderstandMonolog\Handler;

use UnderstandMonolog\Exception\HandlerException;
use UnderstandMonolog\Formatter\UnderstandFormatter;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

abstract class UnderstandBaseHandler extends AbstractProcessingHandler
{

    /**
     * Input token
     *
     * @var string
     */
    protected $inputToken;

    /**
     * API url
     *
     * @var string
     */
    protected $apiUrl;

    /**
     * Specifies whether logger should throw an exception of issues detected
     *
     * @var bool
     */
    protected $silent = true;

    /**
     * SSL CA bundle path
     *
     * @var string
     */
    protected $sslBundlePath;

    /**
     * Last handler error
     *
     * @var string
     */
    protected $lastError;

    /**
     * @param string $inputToken
     * @param string $apiUrl
     * @param boolean $silent
     * @param string $sslBundlePath
     * @param integer $level
     * @param boolean $bubble
     */
    public function __construct($inputToken, $apiUrl = 'https://api.understand.io', $silent = true, $sslBundlePath = false, $level = Logger::DEBUG, $bubble = true)
    {
        if ($sslBundlePath === false)
        {
            $this->sslBundlePath = __DIR__ . DIRECTORY_SEPARATOR . 'ca_bundle.crt';
        }
        else
        {
            $this->sslBundlePath = $sslBundlePath;
        }

        $this->inputToken = $inputToken;
        $this->apiUrl = $apiUrl;
        $this->silent = $silent;


        parent::__construct($level, $bubble);
    }

    /**
     * Send data to storage
     *
     * @param string $data
     * @return string
     */
    abstract protected function send($data);

    /**
     * Serialize data and send to storage
     *
     * @param array $record
     * @return void
     */
    public function write(array $record)
    {
        $requestData = $record['formatted'];

        $response = $this->send($requestData);

        $this->parseResponse($response, $requestData);
    }

    /**
     * Return endpoint
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return implode('/', [$this->apiUrl, $this->inputToken]);
    }

    /**
     * Parse respnse into array
     *
     * @param string $response
     * @param string $requestData
     * @return array
     */
    protected function parseResponse($response, $requestData)
    {
        $responseArr = json_decode($response, true);

        if ( ! $this->silent && empty($responseArr['count']))
        {
            $this->handleError($responseArr, $requestData);
        }

        return $responseArr;
    }

    /**
     * Transform error respopnse into exception
     *
     * @param string $responseArr
     * @param string $requestData
     * @throws HandlerException
     */
    protected function handleError($responseArr, $requestData)
    {
        if ( ! $responseArr)
        {
            throw new HandlerException('Cannot create connection to ' . $this->apiUrl . ' ' . $this->lastError);
        }

        if (isset($responseArr['error']))
        {
            throw new HandlerException($responseArr['error']);
        }

        throw new HandlerException('Error. ' . ' Request data: ' . json_encode($requestData));
    }

    /**
     * Gets the default formatter.
     *
     * @return FormatterInterface
     */
    protected function getDefaultFormatter()
    {
        return new UnderstandFormatter();
    }
}