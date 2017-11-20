<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business;

interface StorageFacadeInterface
{
    /**
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @api
     *
     * @return int
     */
    public function getTotalCount();

    /**
     * @api
     *
     * @return array
     */
    public function getTimestamps();

    /**
     * @api
     *
     * @return int
     */
    public function deleteAll();

    /**
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys);
}
