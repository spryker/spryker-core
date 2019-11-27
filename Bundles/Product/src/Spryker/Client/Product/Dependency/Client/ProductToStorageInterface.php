<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product\Dependency\Client;

interface ProductToStorageInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @param string[] $keys
     *
     * @return array
     */
    public function getMulti(array $keys);
}
