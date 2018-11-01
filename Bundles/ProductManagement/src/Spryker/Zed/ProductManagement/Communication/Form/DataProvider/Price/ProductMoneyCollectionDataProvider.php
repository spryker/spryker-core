<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider\Price;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Price\ProductMoneyCollectionType;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface;

class ProductMoneyCollectionDataProvider
{
    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface $priceProductFacade
     */
    public function __construct(
        ProductManagementToCurrencyInterface $currencyFacade,
        ProductManagementToPriceProductInterface $priceProductFacade
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->priceFacade = $priceProductFacade;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getInitialData()
    {
        $storeCurrencyCollection = $this->currencyFacade->getAllStoresWithCurrencies();
        $priceTypes = $this->priceFacade->getPriceTypeValues();

        $productMoneyValueCollection = new ArrayObject();
        foreach ($storeCurrencyCollection as $storeWithCurrencyTransfer) {
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                foreach ($priceTypes as $priceTypeTransfer) {
                    $priceProductTransfer = $this->mapProductMoneyValueTransfer(
                        $currencyTransfer,
                        $storeWithCurrencyTransfer->getStore(),
                        $priceTypeTransfer
                    );
                    $productMoneyValueCollection->append($priceProductTransfer);
                }
            }
        }

        return $this->mapProductMoneyValueCollection($productMoneyValueCollection);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $currentFormMoneyValueCollection
     *
     * @return \ArrayObject
     */
    public function mergeMissingMoneyValues(ArrayObject $currentFormMoneyValueCollection)
    {
        $storeCurrencyCollection = $this->currencyFacade->getAllStoresWithCurrencies();
        $existingCurrencyMap = $this->createCurrencyIndexMap($currentFormMoneyValueCollection);

        $currentFormMoneyValueCollection = $this->mapProductMoneyValueCollection($currentFormMoneyValueCollection);

        return $this->mergeMultiStoreMoneyCollection(
            $currentFormMoneyValueCollection,
            $storeCurrencyCollection,
            $existingCurrencyMap
        );
    }

    /**
     * @param \ArrayObject $productMoneyValueCollection
     *
     * @return \ArrayObject
     */
    protected function mapProductMoneyValueCollection(ArrayObject $productMoneyValueCollection)
    {
        $mappedProductMoneyValueCollection = new ArrayObject();

        foreach ($productMoneyValueCollection as $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            $identifier = $this->buildItemIdentifier(
                $moneyValueTransfer->getFkStore(),
                $priceProductTransfer->getPriceType(),
                $moneyValueTransfer->getCurrency()
            );

            $mappedProductMoneyValueCollection[$identifier] = $priceProductTransfer;
        }

        return $mappedProductMoneyValueCollection;
    }

    /**
     * @param \ArrayObject $currentFormMoneyValueCollection
     * @param \Generated\Shared\Transfer\StoreWithCurrencyTransfer[] $storeCurrencyCollection
     * @param array $existingCurrencyMap
     *
     * @return \ArrayObject
     */
    protected function mergeMultiStoreMoneyCollection(
        ArrayObject $currentFormMoneyValueCollection,
        array $storeCurrencyCollection,
        array $existingCurrencyMap
    ) {

        $priceTypes = $this->priceFacade->getPriceTypeValues();

        foreach ($storeCurrencyCollection as $storeWithCurrencyTransfer) {
            $storeTransfer = $storeWithCurrencyTransfer->getStore();
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                foreach ($priceTypes as $priceTypeTransfer) {
                    $identifier = $this->buildItemIdentifier(
                        $storeTransfer->getIdStore(),
                        $priceTypeTransfer,
                        $currencyTransfer
                    );

                    if (isset($existingCurrencyMap[$identifier])) {
                        continue;
                    }

                    $currentFormMoneyValueCollection[$identifier] = $this->mapProductMoneyValueTransfer(
                        $currencyTransfer,
                        $storeTransfer,
                        $priceTypeTransfer
                    );
                }
            }
        }

        return $currentFormMoneyValueCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapProductMoneyValueTransfer(
        CurrencyTransfer $currencyTransfer,
        StoreTransfer $storeTransfer,
        PriceTypeTransfer $priceTypeTransfer
    ) {

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setCurrency($currencyTransfer)
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setFkStore($storeTransfer->getIdStore());

        return (new PriceProductTransfer())
            ->setMoneyValue($moneyValueTransfer)
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setPriceType($priceTypeTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $submittedMoneyValueCollection
     *
     * @return array
     */
    protected function createCurrencyIndexMap(ArrayObject $submittedMoneyValueCollection)
    {
        $currencyIndex = [];
        foreach ($submittedMoneyValueCollection as $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

            $identifier = $this->buildItemIdentifier(
                $moneyValueTransfer->getFkStore(),
                $priceProductTransfer->getPriceType(),
                $moneyValueTransfer->getCurrency()
            );

            $currencyIndex[$identifier] = true;
        }
        return $currencyIndex;
    }

    /**
     * @param int $idStore
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return string
     */
    protected function buildItemIdentifier(
        $idStore,
        PriceTypeTransfer $priceTypeTransfer,
        CurrencyTransfer $currencyTransfer
    ) {
        return implode(
            ProductMoneyCollectionType::PRICE_DELIMITER,
            [
                $idStore,
                $currencyTransfer->getIdCurrency(),
                $priceTypeTransfer->getName(),
                $priceTypeTransfer->getPriceModeConfiguration(),
            ]
        );
    }
}
