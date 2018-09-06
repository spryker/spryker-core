<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\LocalizedForm;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\ThresholdType;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;

abstract class AbstractThresholdFormMapper
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeProvider;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface
     */
    protected $storeCurrencyFinder;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface $storeCurrencyFinder
     */
    public function __construct(
        LocaleProvider $localeProvider,
        StoreCurrencyFinderInterface $storeCurrencyFinder
    ) {
        $this->localeProvider = $localeProvider;
        $this->storeCurrencyFinder = $storeCurrencyFinder;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    protected function setStoreAndCurrencyToMinimumOrderValueTransfer(
        array $data,
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $storeTransfer = $this->storeCurrencyFinder->getStoreTransferFromRequest($data[ThresholdType::FIELD_STORE_CURRENCY]);
        $currencyTransfer = $this->storeCurrencyFinder->getCurrencyTransferFromRequest($data[ThresholdType::FIELD_STORE_CURRENCY]);

        $merchantRelationshipMinimumOrderValueTransfer->setStore($storeTransfer);
        $merchantRelationshipMinimumOrderValueTransfer->setCurrency($currencyTransfer);

        return $merchantRelationshipMinimumOrderValueTransfer;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     * @param string $localizedFormPrefix
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    protected function setLocalizedMessagesToMinimumOrderValueTransfer(
        array $data,
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer,
        string $localizedFormPrefix
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $localeCollection = $this->localeProvider->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $localizedFieldName = ThresholdType::getLocalizedFormName($localizedFormPrefix, $localeTransfer->getLocaleName());
            $localizedMessage = (new MinimumOrderValueLocalizedMessageTransfer())
                ->setLocaleCode($localeTransfer->getLocaleName())
                ->setMessage($data[$localizedFieldName][LocalizedForm::FIELD_MESSAGE]);

            $merchantRelationshipMinimumOrderValueTransfer->addLocalizedMessage($localizedMessage);
        }

        return $merchantRelationshipMinimumOrderValueTransfer;
    }
}
