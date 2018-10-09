<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\LocalizedForm;
use Spryker\Zed\SalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface;

abstract class AbstractGlobalThresholdFormMapper
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface
     */
    protected $storeCurrencyFinder;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\SalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface $storeCurrencyFinder
     */
    public function __construct(
        SalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade,
        StoreCurrencyFinderInterface $storeCurrencyFinder
    ) {
        $this->localeFacade = $localeFacade;
        $this->storeCurrencyFinder = $storeCurrencyFinder;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function setStoreAndCurrencyToSalesOrderThresholdTransfer(
        array $data,
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer {
        $storeTransfer = $this->storeCurrencyFinder->getStoreTransferFromRequestParam($data[GlobalThresholdType::FIELD_STORE_CURRENCY]);
        $currencyTransfer = $this->storeCurrencyFinder->getCurrencyTransferFromRequestParam($data[GlobalThresholdType::FIELD_STORE_CURRENCY]);

        $salesOrderThresholdTransfer->setStore($storeTransfer);
        $salesOrderThresholdTransfer->setCurrency($currencyTransfer);

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     * @param string $localizedFormPrefix
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function setLocalizedMessagesToSalesOrderThresholdTransfer(
        array $data,
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer,
        string $localizedFormPrefix
    ): SalesOrderThresholdTransfer {
        $localeCollection = $this->localeFacade->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $localizedFieldName = GlobalThresholdType::getLocalizedFormName($localizedFormPrefix, $localeTransfer->getLocaleName());
            $localizedMessage = (new SalesOrderThresholdLocalizedMessageTransfer())
                ->setLocaleCode($localeTransfer->getLocaleName())
                ->setMessage($data[$localizedFieldName][LocalizedForm::FIELD_MESSAGE]);

            $salesOrderThresholdTransfer->addLocalizedMessage($localizedMessage);
        }

        return $salesOrderThresholdTransfer;
    }
}
