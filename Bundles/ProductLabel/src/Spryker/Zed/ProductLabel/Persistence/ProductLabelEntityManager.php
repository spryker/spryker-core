<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelPersistenceFactory getFactory()
 */
class ProductLabelEntityManager extends AbstractEntityManager implements ProductLabelEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function createProductLabel(ProductLabelTransfer $productLabelTransfer): ProductLabelTransfer
    {
        $productLabelMapper = $this->getFactory()->createProductLabelMapper();

        $productLabelEntity = $productLabelMapper->mapProductLabelTransferToProductLabelEntity(
            $productLabelTransfer,
            new SpyProductLabel()
        );
        $productLabelEntity->save();

        return $productLabelMapper->mapProductLabelEntityToProductLabelTransfer(
            $productLabelEntity,
            $productLabelTransfer
        );
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabel(int $idProductLabel): void
    {
        $this->getFactory()
            ->createProductLabelQuery()
            ->findByIdProductLabel($idProductLabel)
            ->delete();
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabelStoreRelations(int $idProductLabel): void
    {
        $this->getFactory()
            ->createProductLabelStoreQuery()
            ->findByFkProductLabel($idProductLabel)
            ->delete();
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabelLocalizedAttributes(int $idProductLabel): void
    {
        $this->getFactory()
            ->createLocalizedAttributesQuery()
            ->findByFkProductLabel($idProductLabel)
            ->delete();
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabelProductAbstractRelations(int $idProductLabel): void
    {
        $this->getFactory()
            ->createProductRelationQuery()
            ->findByFkProductLabel($idProductLabel)
            ->delete();
    }

    /**
     * @param array $idStores
     * @param int $idProductLabel
     *
     * @return void
     */
    public function removeProductLabelStoreRelationForStores(array $idStores, int $idProductLabel): void
    {
        if ($idStores === []) {
            return;
        }

        $this->getFactory()
            ->createProductLabelStoreQuery()
            ->filterByFkProductLabel($idProductLabel)
            ->filterByFkStore_In($idStores)
            ->find()
            ->delete();
    }

    /**
     * @param array $idStores
     * @param int $idProductLabel
     *
     * @return void
     */
    public function createProductLabelStoreRelationForStores(array $idStores, int $idProductLabel): void
    {
        foreach ($idStores as $idStore) {
            $productLabelStoreEntity = new SpyProductLabelStore();
            $productLabelStoreEntity->setFkStore($idStore)
                ->setFkProductLabel($idProductLabel)
                ->save();
        }
    }
}
