<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Propel\Runtime\Collection\ObjectCollection;

class ProductBundleMapper
{
    /**
     * @param \Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundle[] $productBundleEntities
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function mapProductBundleEntitiesToProductForBundleTransfers(
        array $productBundleEntities
    ): array {
        $productForBundleTransfers = [];
        foreach ($productBundleEntities as $productBundleEntity) {
            $productForBundleTransfers[] = (new ProductForBundleTransfer())->fromArray(
                $productBundleEntity->getSpyProductRelatedByFkBundledProduct()->toArray(),
                true
            );
        }

        return $productForBundleTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductBundle\Persistence\SpyProductBundle[] $productBundleEntities
     * @param \Generated\Shared\Transfer\ProductBundleCollectionTransfer $productBundleCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    public function mapProductBundleEntitiesToProductBundleCollectionTransfer(
        ObjectCollection $productBundleEntities,
        ProductBundleCollectionTransfer $productBundleCollectionTransfer
    ): ProductBundleCollectionTransfer {
        $productBundleTransfers = $this->mapProductBundleEntitiesToProductBundleTransfers($productBundleEntities);

        foreach ($productBundleTransfers as $productBundleTransfer) {
            $productBundleCollectionTransfer->addProductBundle($productBundleTransfer);
        }

        return $productBundleCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductBundle\Persistence\SpyProductBundle[] $productBundleEntities
     *
     * @return \Generated\Shared\Transfer\ProductBundleTransfer[]
     */
    protected function mapProductBundleEntitiesToProductBundleTransfers(ObjectCollection $productBundleEntities): array
    {
        $productBundleTransfersGroupedByIdProductBundle = [];

        foreach ($productBundleEntities as $productBundleEntity) {
            $productForBundleTransfer = $this->mapProductBundleEntityToProductForBundleTransfer($productBundleEntity, new ProductForBundleTransfer());

            if (isset($productBundleTransfersGroupedByIdProductBundle[$productBundleEntity->getFkProduct()])) {
                $productBundleTransfer = $productBundleTransfersGroupedByIdProductBundle[$productBundleEntity->getFkProduct()];
                $productBundleTransfer->addBundledProduct($productForBundleTransfer);
                $productBundleTransfersGroupedByIdProductBundle[$productBundleEntity->getFkProduct()] = $productBundleTransfer;

                break;
            }

            $productBundleTransfer = (new ProductBundleTransfer())
                ->setIdProductConcrete($productBundleEntity->getFkProduct())
                ->addBundledProduct($productForBundleTransfer);

            $productBundleTransfersGroupedByIdProductBundle[$productBundleEntity->getFkProduct()] = $productBundleTransfer;
        }

        return $productBundleTransfersGroupedByIdProductBundle;
    }

    /**
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle $productBundleEntity
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer
     */
    protected function mapProductBundleEntityToProductForBundleTransfer(SpyProductBundle $productBundleEntity, ProductForBundleTransfer $productForBundleTransfer): ProductForBundleTransfer
    {
        $productForBundleTransfer->setIdProductConcrete($productBundleEntity->getFkBundledProduct());
        $productForBundleTransfer->setSku($productBundleEntity->getSpyProductRelatedByFkBundledProduct()->getSku());
        $productForBundleTransfer->fromArray($productBundleEntity->toArray(), true);

        return $productForBundleTransfer;
    }
}
