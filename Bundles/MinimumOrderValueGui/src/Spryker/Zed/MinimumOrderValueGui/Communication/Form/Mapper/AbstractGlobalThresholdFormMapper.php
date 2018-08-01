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
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    protected function setStoreAndCurrencyToMinimumOrderValueTransfer(
        array $data,
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MinimumOrderValueTransfer {
        $storeCurrencyTransfer = $this->storeCurrencyFinder->findStoreCurrencyByString($data[GlobalThresholdType::FIELD_STORE_CURRENCY]);

        $minimumOrderValueTransfer->setStore($storeCurrencyTransfer->getStore());
        $minimumOrderValueTransfer->setCurrency($storeCurrencyTransfer->getCurrency());

        return $minimumOrderValueTransfer;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     * @param string $localizedFormPrefix
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    protected function setLocalizedMessagesToMinimumOrderValueTransfer(
        array $data,
        MinimumOrderValueTransfer $minimumOrderValueTransfer,
        string $localizedFormPrefix
    ): MinimumOrderValueTransfer {
        $localeCollection = $this->localeProvider->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $localizedFieldName = GlobalThresholdType::getLocalizedFormName($localizedFormPrefix, $localeTransfer->getLocaleName());
            $localizedMessage = (new MinimumOrderValueLocalizedMessageTransfer())
                ->setLocaleCode($localeTransfer->getLocaleName())
                ->setFkLocale($localeTransfer->getIdLocale())
                ->setMessage($data[$localizedFieldName][LocalizedForm::FIELD_MESSAGE]);

            $minimumOrderValueTransfer->addLocalizedMessage($localizedMessage);
        }

        return $minimumOrderValueTransfer;
    }
}
