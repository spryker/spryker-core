<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Storage;

/**
 * Class AdapterTrait
 */

trait AdapterTrait
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var mixed
     */
    protected $resource;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param resource $resource
     *
     * @return $this
     */
    protected function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    protected function getResource()
    {
        if (!$this->resource) {
            $this->connect();
        }

        return $this->resource;
    }

    /**
     * @return bool
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     *
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @return void
     */
    abstract public function connect();

}
