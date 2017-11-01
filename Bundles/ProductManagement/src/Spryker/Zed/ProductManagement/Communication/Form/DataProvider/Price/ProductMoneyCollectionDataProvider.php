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
        return $productMoneyValueCollection;
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

        return $this->mergeMultiStoreMoneyCollection(
            $currentFormMoneyValueCollection,
            $storeCurrencyCollection,
            $existingCurrencyMap
        );
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
                    $index = $this->createBucketIndex(
                        $currencyTransfer->getIdCurrency(),
                        $storeTransfer->getIdStore(),
                        $priceTypeTransfer->getName()
                    );

                    if (isset($existingCurrencyMap[$index])) {
                        continue;
                    }

                    $currentFormMoneyValueCollection->append(
                        $this->mapProductMoneyValueTransfer($currencyTransfer, $storeTransfer, $priceTypeTransfer)
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
            $index = $this->createBucketIndex(
                $moneyValueTransfer->getFkCurrency(),
                $moneyValueTransfer->getFkStore(),
                $priceProductTransfer->getPriceTypeName()
            );

            $currencyIndex[$index] = true;
        }
        return $currencyIndex;
    }

    /**
     * @param int $idCurrency
     * @param int $idStore
     * @param string $priceType
     *
     * @return string
     */
    protected function createBucketIndex($idCurrency, $idStore, $priceType)
    {
        return $idCurrency . '-' . $idStore . '-' . $priceType;
    }
}
