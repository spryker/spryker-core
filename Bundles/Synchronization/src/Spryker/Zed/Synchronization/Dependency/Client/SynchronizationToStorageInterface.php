<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Dependency\Client;

interface SynchronizationToStorageInterface
{

    /**
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     * @param string $prefix
     *
     * @return int
     */
    public function set($key, $value, $ttl = null, $prefix = '');

    /**
     * @param string $key
     * @param string $prefix
     *
     * @return array|null
     */
    public function get($key, $prefix = '');

    /**
     * @param string $key
     * @param string $prefix
     *
     * @return void
     */
    public function delete($key, $prefix = '');

}
