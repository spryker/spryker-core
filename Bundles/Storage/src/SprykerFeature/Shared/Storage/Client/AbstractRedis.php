<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Storage\Client;

use Predis\Client;
use Predis\Connection\ConnectionException;

/**
 * @property \Predis\Client $resource
 *
 * @method \Predis\Client getResource()
 */
abstract class AbstractRedis extends AbstractKeyValue
{

    /**
     * @throws \MemcachedException
     */
    public function connect()
    {
        if (!$this->resource) {
            $resource = new Client(
                [
                    'protocol' => $this->config['protocol'],
                    'host' => $this->config['host'],
                    'port' => $this->config['port'],
                ]
            );

            if (!$resource) {
                throw new ConnectionException($resource, 'Could not connect to redis server');
            }

            $this->resource = $resource;
        }
    }

    /**
     * close redis connection
     */
    public function __destruct()
    {
        if ($this->resource) {
            $this->resource->disconnect();
        }
    }

}
