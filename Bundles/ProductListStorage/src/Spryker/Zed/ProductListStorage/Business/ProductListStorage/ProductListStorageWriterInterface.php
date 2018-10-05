<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductListStorage;

interface ProductListStorageWriterInterface
{
    /**
     * @param int[] $productListIds
     *
     * @return void
     */
    public function publish(array $productListIds): void;
}
