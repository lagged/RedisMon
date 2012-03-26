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
        $config = new Config('/does/not/exist/redis-mon.ini', 'foo');
    }

    public function testGetEnvConfig()
    {
        $config = new Config(BASE_DIR . '/etc/redis-mon.ini');
        $config->setEnvironment('testing')->parse();

        $envConfig = $config->getEnvConfig();
        $this->assertInstanceOf('ArrayObject', $envConfig);
    }
}
