<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractTypeTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractType;
use Propel\Runtime\Collection\Collection;

class ProductAbstractTypeMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractType> $productAbstractTypeEntities
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer>
     */
    public function mapProductAbstractTypeEntitiesToProductAbstractTypeTransfers(Collection $productAbstractTypeEntities): array
    {
        $productAbstractTypeTransfers = [];
        foreach ($productAbstractTypeEntities as $productAbstractTypeEntity) {
            $productAbstractTypeTransfers[] = $this->mapProductAbstractTypeEntityToProductAbstractTypeTransfer($productAbstractTypeEntity);
        }

        return $productAbstractTypeTransfers;
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractType $productAbstractTypeEntity
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTypeTransfer
     */
    public function mapProductAbstractTypeEntityToProductAbstractTypeTransfer(
        SpyProductAbstractType $productAbstractTypeEntity
    ): ProductAbstractTypeTransfer {
        return (new ProductAbstractTypeTransfer())
            ->fromArray($productAbstractTypeEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $productAbstractTypeEntities
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer>
     */
    public function mapProductAbstractTypeEntitiesWithVirtualColumnsToProductAbstractTypeTransfers(Collection $productAbstractTypeEntities): array
    {
        $productAbstractTypeTransfers = [];

        foreach ($productAbstractTypeEntities as $productAbstractTypeEntity) {
            $productAbstractTypeTransfer = (new ProductAbstractTypeTransfer())
                ->setIdProductAbstractType($productAbstractTypeEntity->getIdProductAbstractType())
                ->setKey($productAbstractTypeEntity->getKey())
                ->setName($productAbstractTypeEntity->getName());

            foreach ($productAbstractTypeEntity->getProductAbstractToProductAbstractTypes() as $productAbstractToProductAbstractTypes) {
                $productAbstractTypeTransfer->addFkProductAbstract($productAbstractToProductAbstractTypes->getFkProductAbstract());
            }

            $productAbstractTypeTransfers[] = $productAbstractTypeTransfer;
        }

        return $productAbstractTypeTransfers;
    }
}
