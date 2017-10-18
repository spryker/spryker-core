<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
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
    )
    {
        $this->currencyFacade = $currencyFacade;
        $this->priceFacade = $priceProductFacade;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getInitialData()
    {
        $productMoneyValueCollection = new ArrayObject();
        $storeCurrencyCollection = $this->currencyFacade->getAllStoresWithCurrencies();
        $priceTypes = $this->priceFacade->getPriceTypeValues();
        foreach ($storeCurrencyCollection as $storeWithCurrencyTransfer) {
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                foreach ($priceTypes as $priceTypeTransfer) {
                    $productMoneyValueCollection->append(
                        $this->mapProductMoneyValueTransfer(
                            $currencyTransfer,
                            $storeWithCurrencyTransfer->getStore(),
                            $priceTypeTransfer
                        )
                    );
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

                    $index = $currencyTransfer->getIdCurrency() . '-' . $storeTransfer->getIdStore() . '-' . $priceTypeTransfer->getName();

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
    )
    {
        $productMoneyValueTransfer = new PriceProductTransfer();

        $moneyValueTransfer = new MoneyValueTransfer();
        $moneyValueTransfer->setCurrency($currencyTransfer);
        $moneyValueTransfer->setFkCurrency($currencyTransfer->getIdCurrency());
        $moneyValueTransfer->setFkStore($storeTransfer->getIdStore());

        $productMoneyValueTransfer->setMoneyValue($moneyValueTransfer);
        $productMoneyValueTransfer->setFkPriceType($priceTypeTransfer->getIdPriceType()); //@todo use id from PriceTypeTransfer

        $productMoneyValueTransfer->setPriceType($priceTypeTransfer);

        return $productMoneyValueTransfer;
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
            $index = $moneyValueTransfer->getFkCurrency() . '-' . $moneyValueTransfer->getFkStore() . '-' . $priceProductTransfer->getPriceTypeName();
            $currencyIndex[$index] = true;
        }
        return $currencyIndex;
    }

}
