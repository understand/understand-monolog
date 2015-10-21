<?php

use UnderstandMonolog\Processor\UnderstandProcessor;

class UnderstandProcessorTest extends PHPUnit_Framework_TestCase
{
    public function testProcessorResults()
    {
        $fields = ['one' => 'test', 'two' => 'test'];
        $processor = new UnderstandProcessor($fields);
        $record = [
            'message' => 'test'
        ];

        $results = $processor($record);

        $this->assertEquals($record['message'], $results['message']);
        $this->assertEquals($fields['one'], $results['one']);
        $this->assertEquals($fields['two'], $results['two']);
    }

    public function testProcessorCallableField()
    {
        $return = '12345';
        $fields = ['arg' => 'test', 'func' => function() use($return) {
            return $return;
        }];

        $processor = new UnderstandProcessor($fields);

        $record = [
            'message' => 'test'
        ];

        $results = $processor($record);

        $this->assertEquals($return, $results['func']);
        $this->assertEquals($fields['arg'], $results['arg']);
    }
}
