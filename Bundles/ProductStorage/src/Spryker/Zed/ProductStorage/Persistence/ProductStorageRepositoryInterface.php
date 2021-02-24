<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Persistence;

interface ProductStorageRepositoryInterface
{
    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function getProductAttributesGroupedByIdProduct(array $productConcreteIds): array;

    /**
     * @return string[]
     */
    public function getProductAttributeKeys(): array;
}
