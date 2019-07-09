<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector\Dependency\Facade;

interface ProductImageCartConnectorToProductImageInterface
{
    /**
     * @param int $productId
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductId($productId);

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductIdForCurrentLocale(int $idProduct): array;

    /**
     * @param int[] $productIds
     * @param string $productImageSetName
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[][]
     */
    public function getProductImagesByProductIdsAndProductImageSetName(array $productIds, string $productImageSetName): array;
}
