<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductBundleReaderInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function findBundledProductsByIdProductConcrete($idProductConcrete);

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    public function getProductBundleCollectionByAssignedIdProductConcrete(int $idProductConcrete): ProductBundleCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function assignBundledProductsToProductConcrete(ProductConcreteTransfer $productConcreteTransfer);
}
