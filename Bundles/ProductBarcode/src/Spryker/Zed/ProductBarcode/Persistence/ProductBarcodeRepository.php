<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Persistence;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductBarcode\Persistence\ProductBarcodePersistenceFactory getFactory()
 */
class ProductBarcodeRepository extends AbstractRepository implements ProductBarcodeRepositoryInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductById(int $idProduct): ProductConcreteTransfer
    {
        $spyProduct = $this
            ->getFactory()
            ->createSpyProductQuery()
            ->filterByIdProduct($idProduct)
            ->findOne();

        return $this
            ->getFactory()
            ->createProductMapper()
            ->mapSpyProductToProductConcreteTransfer($spyProduct);
    }
}
