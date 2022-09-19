<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MoneyGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MoneyValueCollectionTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Spryker\Zed\MoneyGui\Communication\Mapper\MoneyValueMapperInterface;
use Spryker\Zed\MoneyGui\Dependency\Facade\MoneyGuiToCurrencyFacadeInterface;

class MoneyCollectionTypeMultiStoreCollectionDataProvider extends BaseMoneyCollectionTypeDataProvider implements MoneyCollectionTypeDataProviderInterface
{
    /**
     * @var \Spryker\Zed\MoneyGui\Dependency\Facade\MoneyGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\MoneyGui\Communication\Mapper\MoneyValueMapperInterface
     */
    protected $moneyValueMapper;

    /**
     * @param \Spryker\Zed\MoneyGui\Dependency\Facade\MoneyGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\MoneyGui\Communication\Mapper\MoneyValueMapperInterface $moneyValueMapper
     */
    public function __construct(MoneyGuiToCurrencyFacadeInterface $currencyFacade, MoneyValueMapperInterface $moneyValueMapper)
    {
        $this->currencyFacade = $currencyFacade;
        $this->moneyValueMapper = $moneyValueMapper;
    }

    /**
     * @return \Generated\Shared\Transfer\MoneyValueCollectionTransfer
     */
    public function getMoneyValuesWithCurrenciesForCurrentStore(): MoneyValueCollectionTransfer
    {
        $moneyValueCollectionTransfer = new MoneyValueCollectionTransfer();
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();
        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $moneyValueTransfer = $this->moneyValueMapper->mapCurrencyTransferToMoneyValueTransfer(
                    $currencyTransfer,
                    new MoneyValueTransfer(),
                );

                if ($storeWithCurrencyTransfer->getStore()) {
                    $moneyValueTransfer->setFkStore($storeWithCurrencyTransfer->getStoreOrFail()->getIdStore());
                }

                $moneyValueCollectionTransfer->addMoneyValue($moneyValueTransfer);
            }
        }

        return $moneyValueCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueCollectionTransfer $currentFormMoneyValueCollection
     *
     * @return \Generated\Shared\Transfer\MoneyValueCollectionTransfer
     */
    public function mergeMissingMoneyValues(MoneyValueCollectionTransfer $currentFormMoneyValueCollection): MoneyValueCollectionTransfer
    {
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();

        $existingCurrencyMap = $this->createCurrencyIndexMap($currentFormMoneyValueCollection);

        return $this->mergeMultiStoreMoneyCollection(
            $currentFormMoneyValueCollection,
            $storeWithCurrencyTransfers,
            $existingCurrencyMap,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueCollectionTransfer $currentFormMoneyValueCollection
     * @param array<\Generated\Shared\Transfer\StoreWithCurrencyTransfer> $storeWithCurrencyTransfers
     * @param array<bool> $existingCurrencyMap
     *
     * @return \Generated\Shared\Transfer\MoneyValueCollectionTransfer
     */
    protected function mergeMultiStoreMoneyCollection(
        MoneyValueCollectionTransfer $currentFormMoneyValueCollection,
        array $storeWithCurrencyTransfers,
        array $existingCurrencyMap
    ): MoneyValueCollectionTransfer {
        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            $storeTransfer = $storeWithCurrencyTransfer->getStoreOrFail();
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $currencyMapKey = $currencyTransfer->getIdCurrencyOrFail() . $storeTransfer->getIdStoreOrFail();
                if (isset($existingCurrencyMap[$currencyMapKey])) {
                    continue;
                }

                $moneyValueTransfer = $this->moneyValueMapper->mapCurrencyTransferToMoneyValueTransfer(
                    $currencyTransfer,
                    new MoneyValueTransfer(),
                );

                $moneyValueTransfer->setFkStore($storeTransfer->getIdStoreOrFail());

                $currentFormMoneyValueCollection->addMoneyValue($moneyValueTransfer);
            }
        }

        return $currentFormMoneyValueCollection;
    }
}
