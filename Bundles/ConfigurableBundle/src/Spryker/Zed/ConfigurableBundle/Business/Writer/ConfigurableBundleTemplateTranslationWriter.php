<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;

class ConfigurableBundleTemplateTranslationWriter implements ConfigurableBundleTemplateTranslationWriterInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(ConfigurableBundleToGlossaryFacadeInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return void
     */
    public function saveTranslations(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): void
    {
        $configurableBundleTemplateTransfer->requireName();
        $translationKey = $configurableBundleTemplateTransfer->getName();

        if (!$this->glossaryFacade->hasKey($translationKey)) {
            $this->glossaryFacade->createKey($translationKey);
        }

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

    /**
     * @param string $translationKey
     * @param string $translationValue
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function persistTranslation(string $translationKey, string $translationValue, LocaleTransfer $localeTransfer): void
    {
        if (!$this->glossaryFacade->hasTranslation($translationKey, $localeTransfer)) {
            $this->glossaryFacade->createTranslation($translationKey, $localeTransfer, $translationValue);

            return;
        }

        $this->glossaryFacade->updateTranslation($translationKey, $localeTransfer, $translationValue);
    }
}
