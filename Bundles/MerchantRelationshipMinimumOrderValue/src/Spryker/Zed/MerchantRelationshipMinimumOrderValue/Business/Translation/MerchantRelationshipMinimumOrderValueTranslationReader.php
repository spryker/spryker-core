<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;

class MerchantRelationshipMinimumOrderValueTranslationReader extends AbstractMerchantRelationshipMinimumOrderValueTranslationManager implements MerchantRelationshipMinimumOrderValueTranslationReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function hydrateLocalizedMessages(MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): MerchantRelationshipMinimumOrderValueTransfer
    {
        $storeTransfer = $this->storeFacade
            ->getStoreByName($merchantRelationshipMinimumOrderValueTransfer->getStore()->getName());

        foreach ($storeTransfer->getAvailableLocaleIsoCodes() as $localeIsoCode) {
            $this->initOrUpdateLocalizedMessages(
                $merchantRelationshipMinimumOrderValueTransfer,
                $localeIsoCode
            );
        }

        return $merchantRelationshipMinimumOrderValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     * @param string $localeIsoCode
     *
     * @return void
     */
    protected function initOrUpdateLocalizedMessages(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer,
        string $localeIsoCode
    ): void {
        $translationTransfer = $this->findTranslationValue(
            $this->generateGlossaryKey($merchantRelationshipMinimumOrderValueTransfer),
            $this->createLocaleTransfer($localeIsoCode)
        );

        foreach ($merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()->getLocalizedMessages() as $minimumOrderValueLocalizedMessageTransfer) {
            if ($minimumOrderValueLocalizedMessageTransfer->getLocaleCode() === $localeIsoCode) {
                $minimumOrderValueLocalizedMessageTransfer->setMessage($translationTransfer ? $translationTransfer->getValue() : null);

                return;
            }
        }

        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()->addLocalizedMessage(
            (new MinimumOrderValueLocalizedMessageTransfer())
                ->setLocaleCode($localeIsoCode)
                ->setMessage($translationTransfer ? $translationTransfer->getValue() : null)
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
