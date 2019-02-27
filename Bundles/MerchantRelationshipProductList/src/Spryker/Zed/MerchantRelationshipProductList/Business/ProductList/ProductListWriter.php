<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business\ProductList;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListEntityManagerInterface;

class ProductListWriter implements ProductListWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListEntityManagerInterface
     */
    protected $merchantRelationshipProductListEntityManager;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListEntityManagerInterface $merchantRelationshipProductListEntityManager
     */
    public function __construct(
        MerchantRelationshipProductListEntityManagerInterface $merchantRelationshipProductListEntityManager
    ) {
        $this->merchantRelationshipProductListEntityManager = $merchantRelationshipProductListEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function clearMerchantRelationshipFromProductLists(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): void {
        $merchantRelationshipTransfer->requireIdMerchantRelationship();

        $this->merchantRelationshipProductListEntityManager
            ->clearMerchantRelationshipFromProductLists($merchantRelationshipTransfer);
    }
}
