<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;

class ConfigurableBundleTemplateFormDataProvider
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface
     */
    protected $configurableBundleFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface $configurableBundleFacade
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        ConfigurableBundleGuiToConfigurableBundleFacadeInterface $configurableBundleFacade,
        ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade,
        ConfigurableBundleGuiToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->localeFacade = $localeFacade;
        $this->configurableBundleFacade = $configurableBundleFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param int|null $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function getData(?int $idConfigurableBundleTemplate = null): ConfigurableBundleTemplateTransfer
    {
        if (!$idConfigurableBundleTemplate) {
            return $this->createEmptyConfigurableBundleTemplateTransfer();
        }

        $configurableBundleTemplateTransfer = $this->configurableBundleFacade
            ->findConfigurableBundleTemplateById($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateTransfer) {
            return $this->createEmptyConfigurableBundleTemplateTransfer();
        }

        return $this->updateConfigurableBundleTemplateTransferWithExistingTranslations($configurableBundleTemplateTransfer);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ConfigurableBundleTemplateForm::OPTION_AVAILABLE_LOCALES => $this->getAvailableLocales(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function createEmptyConfigurableBundleTemplateTransfer(): ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateTransfer = new ConfigurableBundleTemplateTransfer();

        foreach ($this->getAvailableLocales() as $localeTransfer) {
            $configurableBundleTemplateTranslationTransfer = new ConfigurableBundleTemplateTranslationTransfer();
            $configurableBundleTemplateTranslationTransfer->setLocale($localeTransfer);

            $configurableBundleTemplateTransfer->addTranslation($configurableBundleTemplateTranslationTransfer);
        }

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function updateConfigurableBundleTemplateTransferWithExistingTranslations(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        $availableLocaleTransfers = $this->getAvailableLocales();

        $translationsByLocales = $this->getTranslationsForLocales(
            $configurableBundleTemplateTransfer->getTranslationKey(),
            $availableLocaleTransfers
        );

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $configurableBundleTemplateTranslationTransfer = new ConfigurableBundleTemplateTranslationTransfer();
            $configurableBundleTemplateTranslationTransfer->setLocale($localeTransfer);
            $configurableBundleTemplateTranslationTransfer->setName($translationsByLocales[$localeTransfer->getIdLocale()] ?? null);

            $configurableBundleTemplateTransfer->addTranslation($configurableBundleTemplateTranslationTransfer);
        }

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getAvailableLocales(): array
    {
        return $this->localeFacade->getLocaleCollection();
    }

    /**
     * @param string $translationKey
     * @param array $localeTransfers
     *
     * @return string[]
     */
    protected function getTranslationsForLocales(string $translationKey, array $localeTransfers): array
    {
        $translationsByLocales = [];

        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeyAndLocales(
            $translationKey,
            $localeTransfers
        );

        foreach ($translationTransfers as $translationTransfer) {
            $translationsByLocales[$translationTransfer->getFkLocale()] = $translationTransfer->getValue();
        }

        return $translationsByLocales;
    }
}
