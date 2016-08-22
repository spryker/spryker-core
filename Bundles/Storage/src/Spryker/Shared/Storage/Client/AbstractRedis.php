<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Storage\Client;

use Predis\Client;
use Predis\Connection\ConnectionException;

/**
 * @deprecated Not used anymore.
 *
 * @property \Predis\Client $resource
 *
 * @method \Predis\Client getResource()
 */
abstract class AbstractRedis extends AbstractKeyValue
{

    /**
     * @throws \Predis\Connection\ConnectionException
     *
     * @return void
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
