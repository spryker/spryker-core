<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Translation;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToStoreFacadeInterface;

/**
 * @deprecated Use `Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator\SalesOrderThresholdTranslationHydratorInterface` instead.
 */
class SalesOrderThresholdTranslationReader implements SalesOrderThresholdTranslationReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        SalesOrderThresholdToGlossaryFacadeInterface $glossaryFacade,
        SalesOrderThresholdToStoreFacadeInterface $storeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function hydrateLocalizedMessages(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): SalesOrderThresholdTransfer
    {
        $storeTransfer = $this->storeFacade
            ->getStoreByName($salesOrderThresholdTransfer->getStore()->getName());

        foreach ($storeTransfer->getAvailableLocaleIsoCodes() as $localeIsoCode) {
            $this->initOrUpdateLocalizedMessages(
                $salesOrderThresholdTransfer,
                $localeIsoCode
            );
        }

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     * @param string $localeIsoCode
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function initOrUpdateLocalizedMessages(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer,
        string $localeIsoCode
    ): SalesOrderThresholdTransfer {
        $translationValue = $this->findTranslationValue(
            $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getMessageGlossaryKey(),
            $this->createLocaleTransfer($localeIsoCode)
        );

        foreach ($salesOrderThresholdTransfer->getLocalizedMessages() as $salesOrderThresholdLocalizedMessageTransfer) {
            if ($salesOrderThresholdLocalizedMessageTransfer->getLocaleCode() === $localeIsoCode) {
                $salesOrderThresholdLocalizedMessageTransfer->setMessage($translationValue);

                return $salesOrderThresholdTransfer;
            }
        }

        $salesOrderThresholdTransfer->addLocalizedMessage(
            (new SalesOrderThresholdLocalizedMessageTransfer())
                ->setLocaleCode($localeIsoCode)
                ->setMessage($translationValue)
        );

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocaleTransfer(string $localeName): LocaleTransfer
    {
        return (new LocaleTransfer())
            ->setLocaleName($localeName);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function findTranslationValue(string $keyName, LocaleTransfer $localeTransfer): ?string
    {
        if (!$this->glossaryFacade->hasTranslation($keyName, $localeTransfer)) {
            return null;
        }

        $translationTransfer = $this->glossaryFacade->getTranslation($keyName, $localeTransfer);

        return $translationTransfer->getValue();
    }
}
