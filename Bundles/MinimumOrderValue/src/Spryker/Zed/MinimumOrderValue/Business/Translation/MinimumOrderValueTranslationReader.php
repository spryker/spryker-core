<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;

class MinimumOrderValueTranslationReader extends AbstractMinimumOrderValueTranslationManager implements MinimumOrderValueTranslationReaderInterface
{
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
            $this->generateGlossaryKey($globalMinimumOrderValueTransfer),
            $this->createLocaleTransfer($localeIsoCode)
        );

        foreach ($globalMinimumOrderValueTransfer->getMinimumOrderValue()->getLocalizedMessages() as $minimumOrderValueLocalizedMessageTransfer) {
            if ($minimumOrderValueLocalizedMessageTransfer->getLocaleCode() === $localeIsoCode) {
                $minimumOrderValueLocalizedMessageTransfer->setMessage($translationValue);

                return;
            }
        }

        $globalMinimumOrderValueTransfer->getMinimumOrderValue()->addLocalizedMessage(
            (new MinimumOrderValueLocalizedMessageTransfer())
                ->setLocaleCode($localeIsoCode)
                ->setMessage($translationValue)
        );
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
