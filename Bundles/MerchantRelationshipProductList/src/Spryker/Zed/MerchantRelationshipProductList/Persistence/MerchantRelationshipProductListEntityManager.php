<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListPersistenceFactory getFactory()
 */
class MerchantRelationshipProductListEntityManager extends AbstractEntityManager implements MerchantRelationshipProductListEntityManagerInterface
{
    /**
     * @param array<int> $productListIds
     * @param int $idMerchantRelationship
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductListTransfer>
     */
    public function assignProductListsToMerchantRelationship(array $productListIds, int $idMerchantRelationship): ArrayObject
    {
        $productListEntities = $this->getFactory()
            ->getProductListQuery()
            ->filterByIdProductList_In($productListIds)
            ->find();

        $merchantRelationshipProductListMapper = $this->getFactory()->createMerchantRelationshipProductListMapper();
        $productListTransfers = [];
        foreach ($productListEntities as $productListEntity) {
            $productListEntity->setFkMerchantRelationship($idMerchantRelationship)
                ->save();

            $productListTransfers[] = $merchantRelationshipProductListMapper->mapProductList(
                $productListEntity,
                new ProductListTransfer(),
            );
        }

        /** @var array<\Generated\Shared\Transfer\ProductListTransfer> $productListTransfers */
        return new ArrayObject($productListTransfers);
    }

    /**
     * @param int $idProductList
     *
     * @return void
     */
    public function clearProductListMerchantRelationship(int $idProductList): void
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
