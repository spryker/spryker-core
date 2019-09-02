<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;

class ConfigurableBundleTemplateFormDataProvider
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
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

        return $this->createConfigurableBundleTemplateTransfer();
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
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function createConfigurableBundleTemplateTransfer(): ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateTransfer = new ConfigurableBundleTemplateTransfer();

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getAvailableLocales(): array
    {
        return $this->localeFacade->getLocaleCollection();
    }
}
