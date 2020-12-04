<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface;

class ProductOfferUpdateFormDataProvider extends AbstractProductOfferFormDataProvider implements ProductOfferUpdateFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface $merchantStockFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade,
        ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductOfferMerchantPortalGuiToProductFacadeInterface $productFacade,
        ProductOfferMerchantPortalGuiToProductOfferFacadeInterface $productOfferFacade,
        ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface $merchantStockFacade,
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        parent::__construct($currencyFacade, $priceProductFacade, $productFacade, $merchantUserFacade, $merchantStockFacade);
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function getData(int $idProductOffer): ?ProductOfferTransfer
    {
        $productOfferTransfer = $this->productOfferFacade->findOne(
            (new ProductOfferCriteriaFilterTransfer())->setIdProductOffer($idProductOffer)
        );

        $currentMerchantId = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant();
        if (!$productOfferTransfer || $currentMerchantId !== $productOfferTransfer->getFkMerchant()) {
            return null;
        }

        /** @var string $sku */
        $sku = $productOfferTransfer->requireConcreteSku()->getConcreteSku();
        /** @var int $idProductConcrete */
        $idProductConcrete = $this->productFacade->findProductConcreteIdBySku($sku);
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $this->productFacade->findProductConcreteById($idProductConcrete);

        $productOfferTransfer->setIdProductConcrete($idProductConcrete);
        $productOfferTransfer = $this->addPrices($productOfferTransfer);
        $productOfferTransfer = $this->setDefaultMerchantStock($productOfferTransfer);

        return $productOfferTransfer;
    }
}
