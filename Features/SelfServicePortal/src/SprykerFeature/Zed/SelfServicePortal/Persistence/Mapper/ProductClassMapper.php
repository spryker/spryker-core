<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductClassCollectionTransfer;
use Generated\Shared\Transfer\ProductClassTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClass;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClass;

class ProductClassMapper
{
    public function mapProductClassEntityToProductClassTransfer(
        SpyProductClass $productClassEntity,
        ProductClassTransfer $productClassTransfer
    ): ProductClassTransfer {
        return $productClassTransfer->fromArray($productClassEntity->toArray(), true);
    }

    public function mapProductToProductClassEntityToProductClassTransfer(
        SpyProductToProductClass $productToProductClassEntity,
        ProductClassTransfer $productClassTransfer
    ): ProductClassTransfer {
        $sku = (string)$productToProductClassEntity->getProduct()->getSku();
        $idProductConcrete = (int)$productToProductClassEntity->getFkProduct();
        $idProductAbstract = (int)$productToProductClassEntity->getProduct()->getFkProductAbstract();

        return $productClassTransfer
            ->fromArray($productToProductClassEntity->getProductClass()->toArray(), true)
            ->setSku($sku)
            ->setIdProductConcrete($idProductConcrete)
            ->setIdProductAbstract($idProductAbstract);
    }

    /**
     * @param array<\Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClass> $productToProductClassEntities
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClassTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductClassTransfer>
     */
    public function mapProductToProductClassEntitiesToProductClassTransfers(
        array $productToProductClassEntities,
        array $productClassTransfers
    ): array {
        foreach ($productToProductClassEntities as $productToProductClassEntity) {
            $productClassTransfers[] = $this->mapProductToProductClassEntityToProductClassTransfer($productToProductClassEntity, new ProductClassTransfer());
        }

        return $productClassTransfers;
    }

    /**
     * @param array<\Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClass> $productToProductClassEntities
     * @param \Generated\Shared\Transfer\ProductClassCollectionTransfer $productClassCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductClassCollectionTransfer
     */
    public function mapProductToProductClassEntitiesToProductClassCollectionTransfer(
        array $productToProductClassEntities,
        ProductClassCollectionTransfer $productClassCollectionTransfer
    ): ProductClassCollectionTransfer {
        $productClassTransfers = new ArrayObject();

        foreach ($productToProductClassEntities as $productToProductClassEntity) {
            $productClassTransfers->append($this->mapProductToProductClassEntityToProductClassTransfer($productToProductClassEntity, new ProductClassTransfer()));
        }

        return $productClassCollectionTransfer->setProductClasses($productClassTransfers);
    }

    /**
     * @param array<\Orm\Zed\SelfServicePortal\Persistence\SpyProductClass> $productClassEntities
     * @param \Generated\Shared\Transfer\ProductClassCollectionTransfer $productClassCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductClassCollectionTransfer
     */
    public function mapProductClassEntitiesToProductClassCollectionTransfer(
        array $productClassEntities,
        ProductClassCollectionTransfer $productClassCollectionTransfer
    ): ProductClassCollectionTransfer {
        $productClassTransfers = [];

        foreach ($productClassEntities as $productClassEntity) {
            $productClassTransfers[] = $this->mapProductClassEntityToProductClassTransfer($productClassEntity, new ProductClassTransfer());
        }

        return $productClassCollectionTransfer->setProductClasses(new ArrayObject($productClassTransfers));
    }
}
