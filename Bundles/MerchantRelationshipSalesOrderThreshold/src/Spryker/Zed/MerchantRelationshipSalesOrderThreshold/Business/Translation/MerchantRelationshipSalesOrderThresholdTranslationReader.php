<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToStoreFacadeInterface;

class MerchantRelationshipSalesOrderThresholdTranslationReader implements MerchantRelationshipSalesOrderThresholdTranslationReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface $glossaryFacade,
        MerchantRelationshipSalesOrderThresholdToStoreFacadeInterface $storeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function hydrateLocalizedMessages(MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): MerchantRelationshipSalesOrderThresholdTransfer
    {
        $storeTransfer = $this->storeFacade
            ->getStoreByName($merchantRelationshipSalesOrderThresholdTransfer->getStore()->getName());

        foreach ($storeTransfer->getAvailableLocaleIsoCodes() as $localeIsoCode) {
            $this->initOrUpdateLocalizedMessages(
                $merchantRelationshipSalesOrderThresholdTransfer,
                $localeIsoCode
            );
        }

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     * @param string $localeIsoCode
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    protected function initOrUpdateLocalizedMessages(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer,
        string $localeIsoCode
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $translationValue = $this->findTranslationValue(
            $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getMessageGlossaryKey(),
            $this->createLocaleTransfer($localeIsoCode)
        );

        foreach ($merchantRelationshipSalesOrderThresholdTransfer->getLocalizedMessages() as $salesOrderThresholdLocalizedMessageTransfer) {
            if ($salesOrderThresholdLocalizedMessageTransfer->getLocaleCode() === $localeIsoCode) {
                $salesOrderThresholdLocalizedMessageTransfer->setMessage($translationValue);

                return $merchantRelationshipSalesOrderThresholdTransfer;
            }
        }

        $merchantRelationshipSalesOrderThresholdTransfer->addLocalizedMessage(
            (new SalesOrderThresholdLocalizedMessageTransfer())
                ->setLocaleCode($localeIsoCode)
                ->setMessage($translationValue)
        );

        return $merchantRelationshipSalesOrderThresholdTransfer;
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
