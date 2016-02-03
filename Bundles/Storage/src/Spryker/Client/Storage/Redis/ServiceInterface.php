<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Storage\Redis;

use Spryker\Client\Storage\StorageClientInterface;

interface ServiceInterface extends StorageClientInterface
{

    /**
     * @param bool $debug
     *
     * @return self
     */
    public function setDebug($debug);

}
