<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\Solr;

use SprykerFeature\Shared\Library\Storage\AdapterInterface;
use SprykerFeature\Shared\Library\Storage\AdapterTrait;
use SprykerFeature\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin\Query;

/**
 * Class Solr
 *
 * @property \Solarium\Client $resource
 *
 * @method \Solarium\Client getResource()
 */
abstract class Solr implements AdapterInterface
{

    use AdapterTrait;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @param array $config
     * @param null $endpoint
     * @param bool $debug
     */
    public function __construct(array $config, $endpoint = null, $debug = false)
    {
        $this->config = $config;
        $this->debug = $debug;
        $this->endpoint = $endpoint;
    }

    /**
     * @return mixed|void
     */
    public function connect()
    {
        if (!$this->resource) {
            $resource = new \Solarium\Client($this->config);
            $resource->registerQueryType(
                Query::QUERY_ADMIN,
                'SprykerFeature\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin\Query'
            );
            if ($this->endpoint) {
                $resource->setDefaultEndpoint($this->endpoint);
            }

            $this->resource = $resource;
        }
    }

    /**
     * close solarium connection
     */
    public function __destruct()
    {
        if ($this->resource) {
            $this->resource = null;
        }
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

}
