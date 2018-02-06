<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business\Model;

interface StorageInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @return int
     */
    public function getTotalCount();

    /**
     * @return array
     */
    public function getTimestamps();

    /**
     * @return int
     */
    public function deleteAll();

    /**
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys);
}
