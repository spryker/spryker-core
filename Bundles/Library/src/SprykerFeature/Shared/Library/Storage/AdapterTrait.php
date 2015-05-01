<?php
namespace SprykerFeature\Shared\Library\Storage;

/**
 * Class AdapterTrait
 * @package SprykerFeature\Shared\Library\DataSource
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
     * @param $resource
     * @return $this
     */
    protected function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getResource()
    {
        if (!$this->resource) {
            $this->connect();
        }

        return $this->resource;
    }

    /**
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param $debug
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
