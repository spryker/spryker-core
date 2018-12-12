<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Persistence;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListPersistenceFactory getFactory()
 */
class MerchantRelationshipProductListEntityManager extends AbstractEntityManager implements MerchantRelationshipProductListEntityManagerInterface
{
    protected const FK_MERCHANT_RELATIONSHIP_KEY = 'FkMerchantRelationship';

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function clearMerchantRelationshipFromProductList(ProductListTransfer $productListTransfer): void
    {
        $productList = $this->getFactory()
            ->getProductListQuery()
            ->findOneByIdProductList($productListTransfer->getIdProductList());

        if (!$productList) {
            return;
        }

        $productList->setFkMerchantRelationship(null)
            ->save();
    }
}
