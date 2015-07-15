<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Storage\Service\Redis;

use SprykerFeature\Client\Storage\Service\StorageClientInterface;

interface ServiceInterface extends StorageClientInterface
{

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config);

    /**
     * @param $debug
     *
     * @return $this
     */
    public function setDebug($debug);

    /**
     * @throws \MemcachedException
     */
    public function connect();

}
