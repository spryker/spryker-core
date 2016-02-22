<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Redis;

use Spryker\Client\Storage\StorageClientInterface;

interface ServiceInterface extends StorageClientInterface
{

    /**
     * @param bool $debug
     *
     * @return $this
     */
    public function setDebug($debug);

}
