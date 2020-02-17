<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelPersistenceFactory getFactory()
 */
class ProductLabelEntityManager extends AbstractEntityManager implements ProductLabelEntityManagerInterface
{
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
    public function addProductLabelStoreRelationForStores(array $idStores, int $idProductLabel): void
    {
        foreach ($idStores as $idStore) {
            $productLabelStoreEntity = new SpyProductLabelStore();
            $productLabelStoreEntity->setFkStore($idStore)
                ->setFkProductLabel($idProductLabel)
                ->save();
        }
    }
}
