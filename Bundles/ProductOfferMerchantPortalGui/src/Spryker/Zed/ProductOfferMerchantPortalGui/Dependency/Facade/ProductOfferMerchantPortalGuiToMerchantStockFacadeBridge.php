<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\StockTransfer;

class ProductOfferMerchantPortalGuiToMerchantStockFacadeBridge implements ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface
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
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function getDefaultMerchantStock(int $idMerchant): StockTransfer
    {
        return $this->merchantStockFacade->getDefaultMerchantStock($idMerchant);
    }
}
