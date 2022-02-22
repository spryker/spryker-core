<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductBundleReaderInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductForBundleTransfer>
     */
    public function findBundledProductsByIdProductConcrete($idProductConcrete);

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface::expandProductConcreteTransfersWithBundledProducts()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function assignBundledProductsToProductConcrete(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithBundledProducts(array $productConcreteTransfers): array;

    /**
     * @param array<string> $skus
     *
     * @return array<array<\Generated\Shared\Transfer\ProductForBundleTransfer>>
     */
    public function getProductForBundleTransfersByProductConcreteSkus(array $skus): array;
}
