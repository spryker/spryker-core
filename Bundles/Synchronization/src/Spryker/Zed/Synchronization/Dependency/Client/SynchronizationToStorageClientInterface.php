<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Dependency\Client;

interface SynchronizationToStorageClientInterface
{
    /**
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return int
     */
    public function set($key, $value, $ttl = null);

    /**
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items);

    /**
     * @param string $key
     *
     * @return array|null
     */
    public function get($key);

    /**
     * @param string $key
     *
     * @return void
     */
    public function delete($key);

    /**
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys);
}
