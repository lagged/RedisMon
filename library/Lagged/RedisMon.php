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
     * This method runs the query against redis-server (via Rediska).
     *
     * The result is always an array where the key is 'server:port'.
     *
     * @return array
     * @throws \RuntimeException
     */
    public function getStats()
    {
        $info = new \Rediska_Command_Info($this->getRediska(), 'INFO');
        if (true !== $info->write()) {
            throw new \RuntimeException("Error issueing command.");
        }
        $stats = $info->read();

        $config = $this->config->getEnvConfig();
        if (count($config->servers) == 1) {
            $server              = $config->servers[0];
            $redisStats[$server] = $stats;
        } else {
            $redisStats = $stats;
        }

        return $redisStats;
    }

    /**
     * Only return the stats requested.
     *
     * Stats are formatted the following way:
     *
     * redis.ROLE.metric = value
     *
     * The metrics are stacked in an array. The returned array's key is always the
     * server the stats came from. This is pretty useful for 'source' in graphite/librato.
     *
     * @return array
     */
    public function stats()
    {
        $stats  = $this->getStats();
        $config = $this->config->getEnvConfig();

        if (empty($config->stats)) {
            throw new \LogicException("There are no stats for collection configured.");
        }

        $collect = array();

        // gather multiple here
        foreach ($stats as $server => $stat) {

            if (!isset($collect[$server])) {
                $collect[$server] = array();
            }

            $keep = array();

            foreach ($config->stats as $toCollect) {
                $prefix        = sprintf('redis.%s.%s', $stat['role'], $toCollect);
                $keep[$prefix] = $stat[$toCollect];
            }
            $collect[$server] = $keep;
            unset($keep);
        }
        unset($stats);
        return $collect;
    }
}
