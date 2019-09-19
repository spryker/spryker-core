<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListPersistenceFactory getFactory()
 */
class MerchantRelationshipProductListEntityManager extends AbstractEntityManager implements MerchantRelationshipProductListEntityManagerInterface
{
    /**
     * @param int[] $productListIds
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function assignProductListsToMerchantRelationship(array $productListIds, int $idMerchantRelationship): void
    {
        $productListEntities = $this->getFactory()
            ->getProductListQuery()
            ->leftJoinWithSpyProductListProductConcrete()
            ->filterByIdProductList_In($productListIds)
            ->find();

        foreach ($productListEntities as $productListEntity) {
            $productListEntity->setFkMerchantRelationship($idMerchantRelationship)
                ->save();
        }
    }

    /**
     * @param int $idProductList
     *
     * @return void
     */
    public function removeMerchantRelationFromProductList(int $idProductList): void
    {
        $productListEntity = $this->getFactory()
            ->getProductListQuery()
            ->findOneByIdProductList($idProductList);

        if (!$productListEntity) {
            return;
        }

        $productListEntity->setSpyMerchantRelationship(null)
            ->save();
    }
}
