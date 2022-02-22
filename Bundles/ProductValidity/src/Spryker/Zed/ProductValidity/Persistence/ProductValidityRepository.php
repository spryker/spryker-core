<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Persistence;

use Generated\Shared\Transfer\ProductValidityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductValidity\Persistence\ProductValidityPersistenceFactory getFactory()
 */
class ProductValidityRepository extends AbstractRepository implements ProductValidityRepositoryInterface
{
    /**
     * Result format:
     * [
     *     $idProductConcrete => ProductValidityTransfer,
     *     ...,
     * ]
     *
     * @param array<int> $productConcreteIds
     *
     * @return array<int, \Generated\Shared\Transfer\ProductValidityTransfer>
     */
    public function getProductValidityTransfersIndexedByIdProductConcrete(array $productConcreteIds): array
    {
        $productValidityEntityCollection = $this->getFactory()->createProductValidityQuery()
            ->filterByFkProduct_In($productConcreteIds)
            ->find();

        $result = [];

        $productValidityMapper = $this->getFactory()->createProductValidityMapper();

        /** @var \Orm\Zed\ProductValidity\Persistence\SpyProductValidity $productValidityEntity */
        foreach ($productValidityEntityCollection as $productValidityEntity) {
            $result[$productValidityEntity->getFkProduct()] = $productValidityMapper->mapProductValidityEntityToProductValidityTransfer(
                $productValidityEntity,
                new ProductValidityTransfer(),
            );
        }

        return $result;
    }
}
