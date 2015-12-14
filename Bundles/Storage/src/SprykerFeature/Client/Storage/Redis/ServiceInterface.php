<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Storage\Redis;

use SprykerFeature\Client\Storage\StorageClientInterface;

interface ServiceInterface extends StorageClientInterface
{

    /**
     * @param $debug
     *
     * @return self
     */
    public function setDebug($debug);

}
