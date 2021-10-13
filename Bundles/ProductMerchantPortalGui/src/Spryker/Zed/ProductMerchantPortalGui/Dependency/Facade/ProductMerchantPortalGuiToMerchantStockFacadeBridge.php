<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;

class ProductMerchantPortalGuiToMerchantStockFacadeBridge implements ProductMerchantPortalGuiToMerchantStockFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface
     */
    protected $merchantStockFacade;

    /**
     * @param \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface $merchantStockFacade
     */
    public function __construct($merchantStockFacade)
    {
        $this->merchantStockFacade = $merchantStockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStockCriteriaTransfer $merchantStockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function get(MerchantStockCriteriaTransfer $merchantStockCriteriaTransfer): StockCollectionTransfer
    {
        return $this->merchantStockFacade->get($merchantStockCriteriaTransfer);
    }
}
