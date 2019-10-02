<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface;

class ConfigurableBundleTranslationExpander implements ConfigurableBundleTranslationExpanderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ConfigurableBundleToGlossaryFacadeInterface $glossaryFacade,
        ConfigurableBundleToLocaleFacadeInterface $localeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function expandConfigurableBundleTemplateWithTranslationForCurrentLocale(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateTransfer->requireName();

        $translation = $this->getTranslation($configurableBundleTemplateTransfer->getName());
        $translation = $this->glossaryFacade->translate($configurableBundleTemplateTransfer->getName());
        $configurableBundleTemplateTransfer->addTranslation(
            (new ConfigurableBundleTemplateTranslationTransfer())->setName($translation)
        );

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function expandConfigurableBundleTemplateWithDefaultLocaleTranslation(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateTransfer->requireName();

        $translation = $this->glossaryFacade->translate(
            $configurableBundleTemplateTransfer->getName(),
            [],
            $this->getDefaultLocale()
        );

        $configurableBundleTemplateTransfer->addTranslation(
            (new ConfigurableBundleTemplateTranslationTransfer())->setName($translation)
        );

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function expandConfigurableBundleTemplateSlotWithTranslationForCurrentLocale(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer->requireName();

        $translation = $this->getTranslation($configurableBundleTemplateSlotTransfer->getName());
        $configurableBundleTemplateSlotTransfer->addTranslation(
            (new ConfigurableBundleTemplateSlotTranslationTransfer())->setName($translation)
        );

        return $configurableBundleTemplateSlotTransfer;
    }

    /**
     * @param string $translationKey
     *
     * @return string
     */
    protected function getTranslation(string $translationKey): string
    {
        if ($this->glossaryFacade->hasTranslation($translationKey)) {
            return $this->glossaryFacade->translate($translationKey);
        }

        return $this->getFallbackTranslation($translationKey);
    }

    /**
     * @param string $translationKey
     *
     * @return string
     */
    protected function getFallbackTranslation(string $translationKey): string
    {
        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeyAndLocales(
            $translationKey,
            $this->localeFacade->getLocaleCollection()
        );

        if ($translationTransfers) {
            return $translationTransfers[0]->getValue();
        }

        return $translationKey;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getDefaultLocale(): LocaleTransfer
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        return reset($localeTransfers);
    }
}
