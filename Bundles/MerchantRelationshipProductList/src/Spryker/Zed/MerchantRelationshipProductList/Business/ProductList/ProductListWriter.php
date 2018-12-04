<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business\ProductList;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListEntityManagerInterface;

class ProductListWriter implements ProductListWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListEntityManagerInterface $entityManager
     */
    public function __construct(MerchantRelationshipProductListEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function clearMerchantRelationshipFromProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        $this->entityManager->clearMerchantRelationshipFromProductList($productListTransfer);
        $productListTransfer->setFkMerchantRelationship(null);

        return $productListTransfer;
    }
}
