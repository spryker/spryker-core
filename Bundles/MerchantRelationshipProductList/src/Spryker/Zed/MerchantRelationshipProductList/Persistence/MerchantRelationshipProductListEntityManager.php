<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListPersistenceFactory getFactory()
 */
class MerchantRelationshipProductListEntityManager extends AbstractEntityManager implements MerchantRelationshipProductListEntityManagerInterface
{
    /**
     * @module ProductList
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function clearMerchantRelationshipFromProductLists(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $productListEntities = $this->getFactory()
            ->getProductListQuery()
            ->filterByFkMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationship())
            ->find();

        if (!$productListEntities->count()) {
            return;
        }

        foreach ($productListEntities as $productListEntity) {
            $productListEntity->setFkMerchantRelationship(null)
                ->save();
        }
    }
}
