<?php
namespace Lagged;

use \Rediska;

/**
 * @author Till Klampaeckel <till@php.net>
 */
class RedisMon
{
    /**
     * @var stdClass $config
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
    public function __construct(\stdClass $config, Rediska $rediska)
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
