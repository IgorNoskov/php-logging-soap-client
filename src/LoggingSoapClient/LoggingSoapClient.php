<?php

namespace IgorNoskov\LoggingSoapClient;

use Psr\Log\LoggerInterface;
use SoapClient;

/**
 * Class LoggingSoapClient.
 */
class LoggingSoapClient
{
    /**
     * @var string
     */
    const REQUEST = 'REQUEST';

    /**
     * @var string
     */
    const RESPONSE = 'RESPONSE';

    /**
     * @var SoapClient
     */
    private $soapClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param SoapClient $soapClient
     * @param LoggerInterface $logger
     */
    public function __construct(SoapClient $soapClient, LoggerInterface $logger)
    {
        $this->soapClient = $soapClient;
        $this->logger     = $logger;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        try {
            return call_user_func_array([$this->soapClient, $method], $arguments);
        } finally {
            $this->logger->info(
                $this->soapClient->__getLastRequestHeaders() . $this->soapClient->__getLastRequest(),
                ['type' => self::REQUEST]
            );
            $this->logger->info(
                $this->soapClient->__getLastResponseHeaders() . $this->soapClient->__getLastResponse(),
                ['type' => self::RESPONSE]
            );
        }
    }
}
