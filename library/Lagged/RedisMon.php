<?php
namespace Lagged;

use \Lagged\RedisMon\Config;
use \Rediska;

/**
 * @author Till Klampaeckel <till@php.net>
 */
class RedisMon
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var \Rediska $rediska
     */
    protected $rediska;

    /**
     * __construct
     *
     * @param \stdClass $config
     * @param \Rediska $rediska
     *
     * @return $this
     */
    public function __construct(Config $config, Rediska $rediska = null)
    {
        $this->config  = $config;
        $this->rediska = $rediska;
    }

    /**
     * Setup an instance of Rediska if none exists.
     *
     * Configuration comes from the configuration file in etc/.
     *
     * @return Rediska
     */
    protected function getRediska()
    {
        if ($this->rediska instanceof Rediska) {
            return $this->rediska;
        }

        $config = $this->config->getEnvConfig();

        if (!isset($config->servers) || empty($config->servers)) {
            throw new \LogicException("No redis-server(s) configured.");
        }

        $servers = array();
        foreach ($config->servers as $server) {
            list($host, $port) = explode(':', $server);
            $servers[]         = array('host' => $host, 'port' => $port);
        }

        $options = array(
            'name'         => $config->rediska->name,
            'addToManager' => true,
            'servers'      => $servers,
        );

        $this->rediska = new Rediska($options);
        return $this->rediska;
    }

    /**
     * @return array
     */
    public function getStats()
    {
        $info = new \Rediska_Command_Info($this->getRediska(), 'INFO');
        if (true !== $info->write()) {
            throw new \RuntimeException("Error issueing command.");
        }
        $stats = $info->read();
        return $stats;
    }

    public function stats()
    {
        $info = $this->rediska->info();
        var_dump($info);
    }
}
