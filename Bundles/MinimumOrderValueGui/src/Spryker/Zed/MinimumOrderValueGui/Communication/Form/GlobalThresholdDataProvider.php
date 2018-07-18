<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form;

use Generated\Shared\Transfer\GlobalThresholdTransfer;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class GlobalThresholdDataProvider implements FormDataProviderInterface
{
    public const STORE_CURRENCY_DELIMITER = ';';

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => GlobalThresholdTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            GlobalThresholdType::OPTION_STORES_ARRAY => $this->getStoreList(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\GlobalThresholdTransfer
     */
    public function getData(Request $request): GlobalThresholdTransfer
    {
        /*
        if ($quoteTransfer->getManualOrder() === null) {
            $quoteTransfer->setManualOrder(new ManualOrderTransfer());
        }

        if ($quoteTransfer->getStore() !== null
            && $quoteTransfer->getCurrency() !== null
        ) {
            $storeName = $quoteTransfer->getStore()->getName();
            $currencyCode = $quoteTransfer->getCurrency()->getCode();

            $quoteTransfer->getManualOrder()->setStoreCurrency($storeName . StoreFormHandler::STORE_CURRENCY_DELIMITER . $currencyCode);
        }
        */

        return new GlobalThresholdTransfer();
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

                $storeList[$storeTransfer->getName() . static::STORE_CURRENCY_DELIMITER . $currencyTransfer->getCode()] = $row;
            }
        }

        return $storeList;
    }
}
