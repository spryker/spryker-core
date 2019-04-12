<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Dependency\Client;

interface HeartbeatToStorageClientInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     *
     * @return mixed
     */
    public function set($key, $value, $ttl = null);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);
}
