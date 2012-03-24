<?php
use Lagged\RedisMon\Test;
use Lagged\RedisMon\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testLogicException()
    {
        new Config('/does/not/exist/redis-mon.ini', 'foo');
    }

    /**
     * @expectedException \LogicException
     */
    public function testSection()
    {
        new Config(BASE_DIR . '/etc/redis-mon.ini', time()); // will never exist
    }
}
