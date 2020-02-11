<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;

class ConfigurableBundleTemplateSlotCreateFormDataProvider
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
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function getData(int $idConfigurableBundleTemplate): ConfigurableBundleTemplateSlotTransfer
    {
        $configurableBundleTemplateSlotTransfer = (new ConfigurableBundleTemplateSlotTransfer())
            ->setFkConfigurableBundleTemplate($idConfigurableBundleTemplate);

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $configurableBundleTemplateSlotTranslationTransfer = new ConfigurableBundleTemplateSlotTranslationTransfer();
            $configurableBundleTemplateSlotTranslationTransfer->setLocale($localeTransfer);

            $configurableBundleTemplateSlotTransfer->addTranslation($configurableBundleTemplateSlotTranslationTransfer);
        }

        return $configurableBundleTemplateSlotTransfer;
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
}
