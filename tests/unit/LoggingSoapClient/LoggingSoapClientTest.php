<?php

namespace IgorNoskov\LoggingSoapClient;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use SoapClient;

/**
 * Class LoggingSoapClientTest.
 */
class LoggingSoapClientTest extends TestCase
{
    /**
     * Tests __call method.
     *
     * @return void
     */
    public function testCall(): void
    {
        $parameters = ['parameters' => 'Example parameters.'];
        $request    = 'Example request.';
        $response   = 'Example response.';

        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->getMockBuilder(LoggerInterface::class)
                       ->getMock();

        $soapClient = $this->getSoapClientMock($request, $response);

        $soapClient->method('__call')
                   ->with('getData', [$parameters])
                   ->will($this->returnValue($response));

        $loggingSoapClient = new LoggingSoapClient($soapClient, $logger);

        $this->assertMessagesAreLogged($logger, $request, $response);
        $this->assertSame($response, $loggingSoapClient->__call('getData', [$parameters]));
    }

    /**
     * Gets the soap client mock.
     *
     * @param string $request
     * @param string $response
     *
     * @return SoapClient|MockObject
     */
    private function getSoapClientMock(string $request, string $response)
    {
        $soapClient = $this->getMockBuilder(SoapClient::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $soapClient->method('__getLastRequest')
                   ->will($this->returnValue($request));

        $soapClient->method('__getLastResponse')
                   ->will($this->returnValue($response));

        return $soapClient;
    }

    /**
     * Asserts that messages are logged.
     *
     * @param MockObject $logger
     * @param string $request
     * @param string $response
     *
     * @return void
     */
    private function assertMessagesAreLogged(MockObject $logger, $request, $response): void
    {
        $logger->expects($this->at(0))
               ->method('info')
               ->with($request, ['type' => LoggingSoapClient::REQUEST]);

        $logger->expects($this->at(1))
               ->method('info')
               ->with($response, ['type' => LoggingSoapClient::RESPONSE]);
    }
}
