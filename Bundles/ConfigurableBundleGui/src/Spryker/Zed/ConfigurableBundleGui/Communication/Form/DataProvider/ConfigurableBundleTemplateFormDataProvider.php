<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
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
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface $configurableBundleFacade
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ConfigurableBundleGuiToConfigurableBundleFacadeInterface $configurableBundleFacade,
        ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->configurableBundleFacade = $configurableBundleFacade;
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
            return $this->createEmptyConfigurableBundleTemplate();
        }

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($idConfigurableBundleTemplate);

        $configurableBundleTemplateTransfer = $this->configurableBundleFacade
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer)
            ->getConfigurableBundleTemplate();

        if (!$configurableBundleTemplateTransfer) {
            return $this->createEmptyConfigurableBundleTemplate();
        }

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ConfigurableBundleTemplateForm::OPTION_AVAILABLE_LOCALES => $this->localeFacade->getLocaleCollection(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function createEmptyConfigurableBundleTemplate(): ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateTransfer = new ConfigurableBundleTemplateTransfer();

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $configurableBundleTemplateTranslationTransfer = new ConfigurableBundleTemplateTranslationTransfer();
            $configurableBundleTemplateTranslationTransfer->setLocale($localeTransfer);

            $configurableBundleTemplateTransfer->addTranslation($configurableBundleTemplateTranslationTransfer);
        }

        return $configurableBundleTemplateTransfer;
    }
}
