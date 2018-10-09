<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\LocalizedForm;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\ThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface;

abstract class AbstractThresholdFormMapper
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface
     */
    protected $storeCurrencyFinder;

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface $storeCurrencyFinder
     */
    public function __construct(
        MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade,
        StoreCurrencyFinderInterface $storeCurrencyFinder
    ) {
        $this->localeFacade = $localeFacade;
        $this->storeCurrencyFinder = $storeCurrencyFinder;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    protected function setStoreAndCurrencyToSalesOrderThresholdTransfer(
        array $data,
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $storeTransfer = $this->storeCurrencyFinder->getStoreTransferFromRequestParam($data[ThresholdType::FIELD_STORE_CURRENCY]);
        $currencyTransfer = $this->storeCurrencyFinder->getCurrencyTransferFromRequestParam($data[ThresholdType::FIELD_STORE_CURRENCY]);

        $merchantRelationshipSalesOrderThresholdTransfer->setStore($storeTransfer);
        $merchantRelationshipSalesOrderThresholdTransfer->setCurrency($currencyTransfer);

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     * @param string $localizedFormPrefix
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    protected function setLocalizedMessagesToSalesOrderThresholdTransfer(
        array $data,
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer,
        string $localizedFormPrefix
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $localeCollection = $this->localeFacade->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $localizedFieldName = ThresholdType::getLocalizedFormName($localizedFormPrefix, $localeTransfer->getLocaleName());
            $localizedMessage = (new SalesOrderThresholdLocalizedMessageTransfer())
                ->setLocaleCode($localeTransfer->getLocaleName())
                ->setMessage($data[$localizedFieldName][LocalizedForm::FIELD_MESSAGE]);

            $merchantRelationshipSalesOrderThresholdTransfer->addLocalizedMessage($localizedMessage);
        }

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }
}
