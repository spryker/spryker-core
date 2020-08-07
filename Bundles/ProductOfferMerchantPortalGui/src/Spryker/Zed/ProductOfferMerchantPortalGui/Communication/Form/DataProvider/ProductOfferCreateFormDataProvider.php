<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface;

class ProductOfferCreateFormDataProvider extends AbstractProductOfferFormDataProvider implements ProductOfferCreateFormDataProviderInterface
{
    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface $merchantStockFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade,
        ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductOfferMerchantPortalGuiToProductFacadeInterface $productFacade,
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface $merchantStockFacade
    ) {
        parent::__construct($currencyFacade, $priceProductFacade, $productFacade, $merchantUserFacade, $merchantStockFacade);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function getData(ProductConcreteTransfer $productConcreteTransfer): ProductOfferTransfer
    {
        $productOfferTransfer = $this->addPrices((new ProductOfferTransfer()));
        $productOfferTransfer = $this->addDefaultValues($productOfferTransfer, $productConcreteTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function addDefaultValues(
        ProductOfferTransfer $productOfferTransfer,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductOfferTransfer {
        $productOfferTransfer->setConcreteSku($productConcreteTransfer->getSku());
        $productOfferTransfer->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete());
        $productOfferTransfer->setFkMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant());
        $productOfferTransfer = $this->setDefaultMerchantStock($productOfferTransfer);

        return $productOfferTransfer;
    }
}
