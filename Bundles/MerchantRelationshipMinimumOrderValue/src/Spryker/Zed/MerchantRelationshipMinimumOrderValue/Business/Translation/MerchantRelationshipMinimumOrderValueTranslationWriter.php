<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

class MerchantRelationshipMinimumOrderValueTranslationWriter extends AbstractMerchantRelationshipMinimumOrderValueTranslationManager implements MerchantRelationshipMinimumOrderValueTranslationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function saveLocalizedMessages(MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): MerchantRelationshipMinimumOrderValueTransfer
    {
        foreach ($merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()->getLocalizedMessages() as $minimumOrderValueLocalizedMessageTransfer) {
            $this->saveTranslation(
                $this->generateGlossaryKey($merchantRelationshipMinimumOrderValueTransfer),
                $this->createLocaleTransfer($minimumOrderValueLocalizedMessageTransfer->getLocaleCode()),
                $minimumOrderValueLocalizedMessageTransfer->getMessage()
            );
        }

        return $merchantRelationshipMinimumOrderValueTransfer;
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function saveTranslation(
        string $keyName,
        LocaleTransfer $localeTransfer,
        string $value
    ): TranslationTransfer {
        if (!$this->glossaryFacade->hasKey($keyName)) {
            $this->glossaryFacade->createKey($keyName);
        }

        if ($this->glossaryFacade->hasTranslation($keyName, $localeTransfer)) {
            return $this->glossaryFacade->updateTranslation($keyName, $localeTransfer, $value);
        }

        return $this->glossaryFacade->createTranslation($keyName, $localeTransfer, $value);
    }
}
