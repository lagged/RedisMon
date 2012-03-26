<?php
namespace Lagged\RedisMon;

/**
 * Small wrapper around parse_ini_file().
 */
class Config extends \IniParser
{
    /**
     * @var \ArrayObject
     */
    protected $config;

    /**
     * This is the current environment's config.
     * @var array
     */
    protected $envConfig;

    /**
     * The environment: production, testing, etc.
     * @var string
     */
    protected $env;

    /**
     * Set the environment for {@link self::getEnvConfig()}.
     *
     * @return \Lagged\RedisMon\Config
     */
    public function setEnvironment($env)
    {
        $this->env = $env;
        return $this;
    }

    /**
     * Save processed config into {@link self::$config}.
     *
     * @param mixed $file
     *
     * @return $this
     */
    public function parse($file = null)
    {
        $this->config = parent::parse($file);
        return $this;
    }

    /**
     * @return \ArrayObject
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the current environment's configuration.
     *
     * @return \ArrayObject
     */
    public function getEnvConfig()
    {
        if ($this->env === null) {
            throw new \RuntimeException("You need to set the environment before asking for it.");
        }
        if (null === $this->envConfig) {
            $this->envConfig = $this->config[$this->env];
        }
        return $this->envConfig;
    }
}
