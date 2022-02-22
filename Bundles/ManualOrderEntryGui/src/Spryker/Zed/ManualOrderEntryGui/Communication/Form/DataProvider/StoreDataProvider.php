<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ManualOrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Store\StoreType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Handler\StoreFormHandler;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeInterface;

class StoreDataProvider implements FormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        ManualOrderEntryGuiToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return array
     */
    public function getOptions($transfer): array
    {
        return [
            'data_class' => QuoteTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            StoreType::OPTION_STORES_ARRAY => $this->getStoreList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($transfer): QuoteTransfer
    {
        if ($transfer->getManualOrder() === null) {
            $transfer->setManualOrder(new ManualOrderTransfer());
        }

        if (
            $transfer->getStore() !== null
            && $transfer->getCurrency() !== null
        ) {
            $storeName = $transfer->getStore()->getName();
            $currencyCode = $transfer->getCurrency()->getCode();

            $transfer->getManualOrder()->setStoreCurrency($storeName . StoreFormHandler::STORE_CURRENCY_DELIMITER . $currencyCode);
        }

        return $transfer;
    }

    /**
     * @return array
     */
    protected function getStoreList(): array
    {
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();
        $storeList = [];

        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            $storeTransfer = $storeWithCurrencyTransfer->getStore();

            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $row = $storeTransfer->getName()
                    . ' - '
                    . $currencyTransfer->getName()
                    . ' [' . $currencyTransfer->getCode() . ']';

                $storeList[$storeTransfer->getName() . StoreFormHandler::STORE_CURRENCY_DELIMITER . $currencyTransfer->getCode()] = $row;
            }
        }

        return $storeList;
    }
}
