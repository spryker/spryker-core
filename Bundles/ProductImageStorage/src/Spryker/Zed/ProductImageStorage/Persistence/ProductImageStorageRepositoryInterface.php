<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

interface ProductImageStorageRepositoryInterface
{
    /**
     * @param array $idsProduct
     *
     * @return array
     */
    public function getProductLocalizedAttributesWithProductByIdProductIn(array $idsProduct): array;

    /**
     * @param array $fksProduct
     * @param array $fksProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getProductImageSetsByFkProductInOrFkAbstractProductIn(array $fksProduct, array $fksProductAbstract): array;
}
