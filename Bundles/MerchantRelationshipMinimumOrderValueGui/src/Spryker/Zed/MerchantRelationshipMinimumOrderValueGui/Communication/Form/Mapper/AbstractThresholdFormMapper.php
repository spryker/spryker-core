<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
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
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    protected function setStoreAndCurrencyToMinimumOrderValueTransfer(
        array $data,
        MinimumOrderValueTransfer $minimumOrderValueTValueTransfer
    ): MinimumOrderValueTransfer {
        $storeTransfer = $this->storeCurrencyFinder->getStoreTransferFromRequest($data[ThresholdType::FIELD_STORE_CURRENCY]);
        $currencyTransfer = $this->storeCurrencyFinder->getCurrencyTransferFromRequest($data[ThresholdType::FIELD_STORE_CURRENCY]);

        $minimumOrderValueTValueTransfer->setStore($storeTransfer);
        $minimumOrderValueTValueTransfer->setCurrency($currencyTransfer);

        return $minimumOrderValueTValueTransfer;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTValueTransfer
     * @param string $localizedFormPrefix
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    protected function setLocalizedMessagesToMinimumOrderValueTransfer(
        array $data,
        MinimumOrderValueTransfer $minimumOrderValueTValueTransfer,
        string $localizedFormPrefix
    ): MinimumOrderValueTransfer {
        $localeCollection = $this->localeProvider->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $localizedFieldName = ThresholdType::getLocalizedFormName($localizedFormPrefix, $localeTransfer->getLocaleName());
            $localizedMessage = (new MinimumOrderValueLocalizedMessageTransfer())
                ->setLocaleCode($localeTransfer->getLocaleName())
                ->setMessage($data[$localizedFieldName][LocalizedForm::FIELD_MESSAGE]);

            $minimumOrderValueTValueTransfer->addLocalizedMessage($localizedMessage);
        }

        return $minimumOrderValueTValueTransfer;
    }
}
