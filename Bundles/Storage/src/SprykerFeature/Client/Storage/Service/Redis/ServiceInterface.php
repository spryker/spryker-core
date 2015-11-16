<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Storage\Service\Redis;

use SprykerFeature\Client\Storage\Service\StorageClientInterface;

interface ServiceInterface extends StorageClientInterface
{

    /**
     * @param $debug
     *
     * @return self
     */
    public function setDebug($debug);

}
