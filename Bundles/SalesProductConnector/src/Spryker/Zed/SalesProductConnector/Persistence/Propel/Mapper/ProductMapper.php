<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class ProductMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProduct[] $productEntities
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function mapProductEntityCollectionToRawProductConcreteTransfers(ObjectCollection $productEntities): array
    {
        $productConcreteTransfer = [];

        foreach ($productEntities as $productEntity) {
            $productConcreteTransfer[] = (new ProductConcreteTransfer())
                ->fromArray($productEntity->toArray(), true)
                ->setIdProductConcrete($productEntity->getIdProduct());
        }

        return $productConcreteTransfer;
    }
}
