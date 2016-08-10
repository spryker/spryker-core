<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage\Adapter\KeyValue;

use Memcached;
use MemcachedException;

/**
 * @property \Memcached $resource
 *
 * @method \Memcached getResource()
 */

abstract class Memcached extends AbstractKeyValue
{

    /**
     * @throws \MemcachedException
     *
     * @return void
     */
    public function connect()
    {
        if (!$this->resource) {
            $resource = new Memcached();
            $resource->addServer(
                isset($this->config['host']) ? $this->config['host'] : '',
                isset($this->config['port']) ? $this->config['port'] : null
            );

            //ensure that values from multi calls are returned in the same order as requested
            //@see http://www.php.net/manual/de/memcached.constants.php
            $resource->setOption(Memcached::GET_PRESERVE_ORDER, true);

            $resource->getVersion();
            if ($resource->getResultCode() !== 0) {
                throw new MemcachedException('Could not connect to any memcached server');
            }

            $this->resource = $resource;
        }
    }

    /**
     * close memcache connection
     */
    public function __destruct()
    {
        if ($this->resource) {
            $this->resource->quit();
        }
    }

}
