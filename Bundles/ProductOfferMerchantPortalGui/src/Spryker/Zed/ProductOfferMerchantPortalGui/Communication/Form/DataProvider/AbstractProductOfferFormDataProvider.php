<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\ProductOfferCreateForm;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface;

abstract class AbstractProductOfferFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface
     */
    protected $merchantStockFacade;

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
        $this->currencyFacade = $currencyFacade;
        $this->priceProductFacade = $priceProductFacade;
        $this->productFacade = $productFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->merchantStockFacade = $merchantStockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int[][]
     */
    public function getOptions(ProductAbstractTransfer $productAbstractTransfer): array
    {
        return [
            ProductOfferCreateForm::OPTION_STORE_CHOICES => $this->getStoreChoices($productAbstractTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function addPrices(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();
        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();

        $indexedPriceProductTransfers = $this->indexPriceProductTransfers($productOfferTransfer);
        $priceProductTransfers = new ArrayObject();
        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                foreach ($priceTypeTransfers as $priceTypeTransfer) {
                    $idStore = $storeWithCurrencyTransfer->getStore()->getIdStore();
                    $idCurrency = $currencyTransfer->getIdCurrency();
                    $idPriceType = $priceTypeTransfer->getIdPriceType();

                    $priceProductTransfer = $indexedPriceProductTransfers[$idStore][$idCurrency][$idPriceType]
                        ?? $this->createDefaultPriceProductTransfer(
                            $currencyTransfer,
                            $storeWithCurrencyTransfer->getStore(),
                            $priceTypeTransfer
                        );

                    $priceProductTransfers->append($priceProductTransfer);
                }
            }
        }

        $productOfferTransfer->setPrices($priceProductTransfers);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int[]
     */
    protected function getStoreChoices(ProductAbstractTransfer $productAbstractTransfer): array
    {
        $storeChoices = [];

        $storeRelationTransfer = $productAbstractTransfer->getStoreRelation();

        if (!$storeRelationTransfer) {
            return $storeChoices;
        }

        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeChoices[$storeTransfer->getName()] = $storeTransfer->getIdStore();
        }

        return $storeChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createDefaultPriceProductTransfer(
        CurrencyTransfer $currencyTransfer,
        StoreTransfer $storeTransfer,
        PriceTypeTransfer $priceTypeTransfer
    ): PriceProductTransfer {
        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer)
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setFkStore($storeTransfer->getIdStore());

        return (new PriceProductTransfer())
            ->setMoneyValue($moneyValueTransfer)
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setPriceType($priceTypeTransfer)
            ->setPriceDimension(new PriceProductDimensionTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]|mixed[]
     */
    protected function indexPriceProductTransfers(ProductOfferTransfer $productOfferTransfer): array
    {
        $indexedPriceProductTransfers = [];
        foreach ($productOfferTransfer->getPrices() as $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            $idStore = $moneyValueTransfer->getFkStore();
            $idCurrency = $moneyValueTransfer->getFkCurrency();
            $idPriceType = $priceProductTransfer->getPriceType()->getIdPriceType();

            $indexedPriceProductTransfers[$idStore][$idCurrency][$idPriceType] = $priceProductTransfer;
        }

        return $indexedPriceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function setMerchantStock(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        if ($productOfferTransfer->getProductOfferStock()) {
            return $productOfferTransfer;
        }

        $productOfferStockTransfer = (new ProductOfferStockTransfer())->setStock(
            $this->merchantStockFacade->getDefaultMerchantStock($productOfferTransfer->getFkMerchant())
        );
        $productOfferTransfer->setProductOfferStock($productOfferStockTransfer);

        return $productOfferTransfer;
    }
}
