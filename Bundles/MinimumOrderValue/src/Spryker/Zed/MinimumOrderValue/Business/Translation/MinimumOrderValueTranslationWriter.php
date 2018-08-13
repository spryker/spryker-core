<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

class MinimumOrderValueTranslationWriter extends AbstractMinimumOrderValueTranslationManager implements MinimumOrderValueTranslationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function saveLocalizedMessages(GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer): GlobalMinimumOrderValueTransfer
    {
        foreach ($globalMinimumOrderValueTransfer->getMinimumOrderValue()->getLocalizedMessages() as $minimumOrderValueLocalizedMessageTransfer) {
            $this->saveTranslation(
                $this->generateGlossaryKey($globalMinimumOrderValueTransfer),
                $this->createLocaleTransfer($minimumOrderValueLocalizedMessageTransfer->getLocaleCode()),
                $minimumOrderValueLocalizedMessageTransfer->getMessage()
            );
        }

        return $globalMinimumOrderValueTransfer;
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
