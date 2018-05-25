<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductPersistenceFactory getFactory()
 */
class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isProductConcreteActive(ProductConcreteTransfer $productConcreteTransfer): bool
    {
        return $this->getFactory()
            ->createProductQuery()
            ->findOneBySku($productConcreteTransfer->getSku())
            ->getIsActive();
    }
}
