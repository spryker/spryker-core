<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;

class ConfigurableBundleTemplateTranslationWriter extends AbstractConfigurableBundleTranslationWriter implements ConfigurableBundleTemplateTranslationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return void
     */
    public function saveTranslations(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): void
    {
        $configurableBundleTemplateTransfer->requireName();
        $translationKey = $configurableBundleTemplateTransfer->getName();

        $this->createTranaslationKeyIfNotExists($translationKey);

        foreach ($configurableBundleTemplateTransfer->getTranslations() as $configurableBundleTemplateTranslationTransfer) {
            $this->persistNameTranslation($configurableBundleTemplateTranslationTransfer, $translationKey);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer $configurableBundleTemplateTranslationTransfer
     * @param string $translationKey
     *
     * @return void
     */
    protected function persistNameTranslation(
        ConfigurableBundleTemplateTranslationTransfer $configurableBundleTemplateTranslationTransfer,
        string $translationKey
    ): void {
        $configurableBundleTemplateTranslationTransfer
            ->requireName()
            ->requireLocale();

        $this->persistTranslation(
            $translationKey,
            $configurableBundleTemplateTranslationTransfer->getName(),
            $configurableBundleTemplateTranslationTransfer->getLocale()
        );
    }
}
