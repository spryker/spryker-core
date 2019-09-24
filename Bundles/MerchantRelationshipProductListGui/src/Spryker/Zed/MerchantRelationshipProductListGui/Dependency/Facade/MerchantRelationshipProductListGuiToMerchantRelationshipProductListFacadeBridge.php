<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\ProductListCollectionTransfer;

class MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeBridge implements MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipProductList\Business\MerchantRelationshipProductListFacadeInterface
     */
    protected $merchantRelationshipProductListFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductList\Business\MerchantRelationshipProductListFacadeInterface $merchantRelationshipProductListFacade
     */
    public function __construct($merchantRelationshipProductListFacade)
    {
        $this->merchantRelationshipProductListFacade = $merchantRelationshipProductListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListCollectionTransfer
     */
    public function getAvailableProductListsForMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): ProductListCollectionTransfer
    {
        return $this->merchantRelationshipProductListFacade->getAvailableProductListsForMerchantRelationship($merchantRelationshipTransfer);
    }
}
