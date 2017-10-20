<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue;

interface ReadInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys);

    /**
     * @return array
     */
    public function getStats();

    /**
     * @return array
     */
    public function getAllKeys();

    /**
     * @return void
     */
    public function resetAccessStats();

    /**
     * @return array
     */
    public function getAccessStats();

    /**
     * @return int
     */
    public function getCountItems();
}
