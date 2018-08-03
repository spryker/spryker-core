<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\LocalizedForm;
use Spryker\Zed\MinimumOrderValueGui\Communication\Model\StoreCurrencyFinder;

abstract class AbstractGlobalThresholdFormMapper
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeProvider;

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Communication\Model\StoreCurrencyFinder
     */
    protected $storeCurrencyFinder;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param \Spryker\Zed\MinimumOrderValueGui\Communication\Model\StoreCurrencyFinder $storeCurrencyFinder
     */
    public function __construct(
        LocaleProvider $localeProvider,
        StoreCurrencyFinder $storeCurrencyFinder
    ) {
        $this->localeProvider = $localeProvider;
        $this->storeCurrencyFinder = $storeCurrencyFinder;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    protected function setStoreAndCurrencyToGlobalMinimumOrderValueTransfer(
        array $data,
        GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
    ): GlobalMinimumOrderValueTransfer {
        $storeCurrencyTransfer = $this->storeCurrencyFinder->findStoreCurrencyByString($data[GlobalThresholdType::FIELD_STORE_CURRENCY]);

        $globalMinimumOrderValueTransfer->setStore($storeCurrencyTransfer->getStore());
        $globalMinimumOrderValueTransfer->setCurrency($storeCurrencyTransfer->getCurrency());

        return $globalMinimumOrderValueTransfer;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     * @param string $localizedFormPrefix
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    protected function setLocalizedMessagesToGlobalMinimumOrderValueTransfer(
        array $data,
        GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer,
        string $localizedFormPrefix
    ): GlobalMinimumOrderValueTransfer {
        $localeCollection = $this->localeProvider->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $localizedFieldName = GlobalThresholdType::getLocalizedFormName($localizedFormPrefix, $localeTransfer->getLocaleName());
            $localizedMessage = (new MinimumOrderValueLocalizedMessageTransfer())
                ->setLocaleCode($localeTransfer->getLocaleName())
                ->setFkLocale($localeTransfer->getIdLocale())
                ->setMessage($data[$localizedFieldName][LocalizedForm::FIELD_MESSAGE]);

            $globalMinimumOrderValueTransfer->getMinimumOrderValue()->addLocalizedMessage($localizedMessage);
        }

        return $globalMinimumOrderValueTransfer;
    }
}
