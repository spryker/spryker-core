<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage;

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
     * @return self
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
     * @param $resource
     *
     * @return self
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
     * @param $debug
     *
     * @return self
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     */
    abstract public function connect();

}
