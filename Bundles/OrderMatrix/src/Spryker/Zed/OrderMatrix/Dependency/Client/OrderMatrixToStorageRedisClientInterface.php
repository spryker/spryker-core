<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\OrderMatrix\Dependency\Client;

interface OrderMatrixToStorageRedisClientInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return mixed
     */
    public function set(string $key, string $value, ?int $ttl = null);
}
