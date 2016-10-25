<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage\Adapter\Solr;

use Solarium\Client;
use Spryker\Shared\Library\Storage\AdapterInterface;
use Spryker\Shared\Library\Storage\AdapterTrait;
use Spryker\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin\Query;

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
     * @param string|null $endpoint
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
            $resource = new Client($this->config);
            $resource->registerQueryType(
                Query::QUERY_ADMIN,
                'Spryker\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin\Query'
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
     *
     * @return void
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

}
