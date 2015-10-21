<?php

use Monolog\Logger;

class UnderstandSyncHandlerTest extends PHPUnit_Framework_TestCase
{
    public function testWrite()
    {
        $handler = $this->getMock('UnderstandMonolog\Handler\UnderstandSyncHandler', ['send'], ['1234', 'http://localhost', false]);

        $handler->expects($this->once())
            ->method('send')
            ->will($this->returnValue('{"status": true, "count":1}'));

        $record = $this->getRecord();

        $handler->handle($record);
    }

    public function testSilentMode()
    {
        $silent = true;
        $handler = $this->getMock('UnderstandMonolog\Handler\UnderstandSyncHandler', ['send'], ['1234', 'http://localhost', $silent]);

        $handler->expects($this->once())
            ->method('send')
            ->will($this->returnValue('{"status": true, "count":0}'));

        $record = $this->getRecord();

        // this should not throw an exception
        $handler->handle($record);
    }

    public function testSilentModeOff()
    {
        $silent = false;
        $handler = $this->getMock('UnderstandMonolog\Handler\UnderstandSyncHandler', ['send'], ['1234', 'http://localhost', $silent]);

        $handler->expects($this->once())
            ->method('send')
            ->will($this->returnValue('{"status": true, "count":0}'));

        $record = $this->getRecord();

        try
        {
            $handler->handle($record);

            $this->fail();
        }
        catch (\UnderstandMonolog\Exception\HandlerException $ex)
        {}
    }

    protected function getRecord()
    {
        $datetime = \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)));

        return ['message' => 'test', 'level' => Logger::ALERT, 'datetime' => $datetime];
    }
}
