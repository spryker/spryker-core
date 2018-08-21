<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageStorage;

interface ProductConcreteImageStorageWriterInterface
{
    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function updateProductConcreteImageStorageSkus(array $productConcreteIds): void;
}
