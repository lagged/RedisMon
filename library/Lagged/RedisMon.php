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
     *
     */
    public function stats()
    {
        $info = $this->rediska->info();
        var_dump($info);
    }
}
