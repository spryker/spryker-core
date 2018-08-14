<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeInterface;

class MinimumOrderValueTranslationReader implements MinimumOrderValueTranslationReaderInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MinimumOrderValueToGlossaryFacadeInterface $glossaryFacade,
        MinimumOrderValueToStoreFacadeInterface $storeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function hydrateLocalizedMessages(GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer): GlobalMinimumOrderValueTransfer
    {
        $storeTransfer = $this->storeFacade
            ->getStoreByName($globalMinimumOrderValueTransfer->getStore()->getName());

        foreach ($storeTransfer->getAvailableLocaleIsoCodes() as $localeIsoCode) {
            $this->initOrUpdateLocalizedMessages(
                $globalMinimumOrderValueTransfer,
                $localeIsoCode
            );
        }

        return $globalMinimumOrderValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     * @param string $localeIsoCode
     *
     * @return void
     */
    protected function initOrUpdateLocalizedMessages(
        GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer,
        string $localeIsoCode
    ): void {
        $translationValue = $this->findTranslationValue(
            $globalMinimumOrderValueTransfer->getMinimumOrderValue()->getMessageGlossaryKey(),
            $this->createLocaleTransfer($localeIsoCode)
        );

        foreach ($globalMinimumOrderValueTransfer->getLocalizedMessages() as $minimumOrderValueLocalizedMessageTransfer) {
            if ($minimumOrderValueLocalizedMessageTransfer->getLocaleCode() === $localeIsoCode) {
                $minimumOrderValueLocalizedMessageTransfer->setMessage($translationValue);

                return;
            }
        }

        $globalMinimumOrderValueTransfer->addLocalizedMessage(
            (new MinimumOrderValueLocalizedMessageTransfer())
                ->setLocaleCode($localeIsoCode)
                ->setMessage($translationValue)
        );
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
     * @return null|string
     */
    protected function findTranslationValue(string $keyName, LocaleTransfer $localeTransfer): ?string
    {
        if ($this->glossaryFacade->hasTranslation($keyName, $localeTransfer)) {
            $translationTransfer = $this->glossaryFacade->getTranslation($keyName, $localeTransfer);

            return $translationTransfer->getValue();
        }

        return null;
    }
}
