<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;

class ConfigurableBundleTemplateSlotTranslationWriter extends AbstractConfigurableBundleTranslationWriter implements ConfigurableBundleTemplateSlotTranslationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return void
     */
    public function saveTranslations(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): void
    {
        $configurableBundleTemplateSlotTransfer->requireName();
        $translationKey = $configurableBundleTemplateSlotTransfer->getName();

        $this->createTranaslationKeyIfNotExists($translationKey);

        foreach ($configurableBundleTemplateSlotTransfer->getTranslations() as $configurableBundleTemplateSlotTranslationTransfer) {
            $this->persistNameTranslation($configurableBundleTemplateSlotTranslationTransfer, $translationKey);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer $configurableBundleTemplateSlotTranslationTransfer
     * @param string $translationKey
     *
     * @return void
     */
    protected function persistNameTranslation(
        ConfigurableBundleTemplateSlotTranslationTransfer $configurableBundleTemplateSlotTranslationTransfer,
        string $translationKey
    ): void {
        $configurableBundleTemplateSlotTranslationTransfer
            ->requireName()
            ->requireLocale();

        $this->persistTranslation(
            $translationKey,
            $configurableBundleTemplateSlotTranslationTransfer->getName(),
            $configurableBundleTemplateSlotTranslationTransfer->getLocale()
        );
    }
}
