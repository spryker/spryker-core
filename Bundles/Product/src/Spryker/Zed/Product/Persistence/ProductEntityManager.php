<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductPersistenceFactory getFactory()
 */
class ProductEntityManager extends AbstractEntityManager implements ProductEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function createProductConcreteCollection(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): ProductConcreteCollectionTransfer {
        $productEntityCollection = new ObjectCollection();
        $productEntityCollection->setModel(SpyProduct::class);

        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $productConcreteTransfer->requireSku()->requireFkProductAbstract();

            $productEntityCollection->append(
                $productMapper->mapProductConcreteTransferToProductEntity($productConcreteTransfer, new SpyProduct())
            );
        }

        $productEntityCollection->save();

        return $productMapper->mapProductEntityCollectionPrimaryKeysToProductConcreteCollectionTransfer(
            $productEntityCollection,
            $productConcreteCollectionTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return void
     */
    public function createProductConcreteCollectionLocalizedAttributes(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        $productLocalizedAttributesEntityCollection = new ObjectCollection();
        $productLocalizedAttributesEntityCollection->setModel(SpyProductLocalizedAttributes::class);

        $localizedAttributesMapper = $this->getFactory()->createLocalizedAttributesMapper();

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $idProductConcrete = $productConcreteTransfer->getIdProductConcreteOrFail();

            foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
                $localizedAttributesTransfer->requireName()->requireLocale();

                $productLocalizedAttributesEntityCollection->append(
                    $localizedAttributesMapper->mapLocalizedAttributesTransferToProductLocalizedAttributesEntity(
                        $localizedAttributesTransfer,
                        (new SpyProductLocalizedAttributes())->setFkProduct($idProductConcrete)
                    )
                );
            }
        }

        $productLocalizedAttributesEntityCollection->save();
    }
}
