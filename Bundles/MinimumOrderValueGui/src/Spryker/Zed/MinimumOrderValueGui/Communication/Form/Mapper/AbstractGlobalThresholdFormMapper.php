<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\LocalizedForm;
use Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;

abstract class AbstractGlobalThresholdFormMapper
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeProvider;

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface
     */
    protected $storeCurrencyFinder;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param \Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface $storeCurrencyFinder
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
        $storeTransfer = $this->storeCurrencyFinder->getStoreTransferFromRequestParam($data[GlobalThresholdType::FIELD_STORE_CURRENCY]);
        $currencyTransfer = $this->storeCurrencyFinder->getCurrencyTransferFromRequestParam($data[GlobalThresholdType::FIELD_STORE_CURRENCY]);

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
            $localizedFieldName = GlobalThresholdType::getLocalizedFormName($localizedFormPrefix, $localeTransfer->getLocaleName());
            $localizedMessage = (new MinimumOrderValueLocalizedMessageTransfer())
                ->setLocaleCode($localeTransfer->getLocaleName())
                ->setMessage($data[$localizedFieldName][LocalizedForm::FIELD_MESSAGE]);

            $minimumOrderValueTValueTransfer->addLocalizedMessage($localizedMessage);
        }

        return $minimumOrderValueTValueTransfer;
    }
}
