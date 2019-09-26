<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;

class ConfigurableBundleTemplateSlotFormDataProvider
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
        $this->configurableBundleFacade = $configurableBundleFacade;
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     * @param int|null $idConfigurableBundleTemplateSlot
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function getData(int $idConfigurableBundleTemplate, ?int $idConfigurableBundleTemplateSlot = null): ConfigurableBundleTemplateSlotTransfer
    {
        if (!$idConfigurableBundleTemplateSlot) {
            return $this->createEmptyConfigurableBundleTemplateSlotTransfer($idConfigurableBundleTemplate);
        }

        return new ConfigurableBundleTemplateSlotTransfer();
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
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    protected function createEmptyConfigurableBundleTemplateSlotTransfer(int $idConfigurableBundleTemplate): ConfigurableBundleTemplateSlotTransfer
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
}
