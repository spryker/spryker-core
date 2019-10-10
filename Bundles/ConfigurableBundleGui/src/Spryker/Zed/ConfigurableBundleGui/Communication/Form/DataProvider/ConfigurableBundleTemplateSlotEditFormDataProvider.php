<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;

class ConfigurableBundleTemplateSlotEditFormDataProvider
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
     * @var \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormDataProviderExpanderPluginInterface[]
     */
    protected $configurableBundleTemplateSlotEditFormDataProviderExpanderPlugins;

    /**
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface $configurableBundleFacade
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormDataProviderExpanderPluginInterface[] $configurableBundleTemplateSlotEditFormDataProviderExpanderPlugins
     */
    public function __construct(
        ConfigurableBundleGuiToConfigurableBundleFacadeInterface $configurableBundleFacade,
        ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade,
        ConfigurableBundleGuiToGlossaryFacadeInterface $glossaryFacade,
        array $configurableBundleTemplateSlotEditFormDataProviderExpanderPlugins
    ) {
        $this->configurableBundleFacade = $configurableBundleFacade;
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->configurableBundleTemplateSlotEditFormDataProviderExpanderPlugins = $configurableBundleTemplateSlotEditFormDataProviderExpanderPlugins;
    }

    /**
     * @param int $idConfigurableBundleTemplateSlot
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer
     */
    public function getData(int $idConfigurableBundleTemplateSlot): ConfigurableBundleTemplateSlotEditFormTransfer
    {
        $configurableBundleTemplateSlotEditFormTransfer = new ConfigurableBundleTemplateSlotEditFormTransfer();

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())
            ->setIdConfigurableBundleTemplateSlot($idConfigurableBundleTemplateSlot);

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleFacade
            ->findConfigurableBundleTemplateSlot($configurableBundleTemplateFilterTransfer);

        if (!$configurableBundleTemplateSlotTransfer) {
            $configurableBundleTemplateSlotEditFormTransfer;
        }

        $productListAggregateFormTransfer = (new ProductListAggregateFormTransfer())->setProductList(
            $configurableBundleTemplateSlotTransfer->getProductList()
        );

        $configurableBundleTemplateSlotEditFormTransfer->setProductListAggregateForm($productListAggregateFormTransfer)
            ->setConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        $configurableBundleTemplateSlotEditFormTransfer = $this->expandDataWithDataProviderExpanderPlugins($configurableBundleTemplateSlotEditFormTransfer);

        $configurableBundleTemplateSlotTransfer = $this->expandConfigurableBundleTemplateSlotTransferWithExistingTranslations(
            $configurableBundleTemplateSlotEditFormTransfer->getConfigurableBundleTemplateSlot()
        );

        return $configurableBundleTemplateSlotEditFormTransfer->setConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $options = [
            ConfigurableBundleTemplateForm::OPTION_AVAILABLE_LOCALES => $this->localeFacade->getLocaleCollection(),
        ];

        return $this->expandOptionsWithDataProviderExpanderPlugins($options);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    protected function expandConfigurableBundleTemplateSlotTransferWithExistingTranslations(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $availableLocaleTransfers = $this->localeFacade->getLocaleCollection();

        $translationsByLocales = $this->getTranslationsByLocales(
            $configurableBundleTemplateSlotTransfer->getName(),
            $availableLocaleTransfers
        );

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $configurableBundleTemplateSlotTranslationTransfer = (new ConfigurableBundleTemplateSlotTranslationTransfer())
                ->setName($translationsByLocales[$localeTransfer->getIdLocale()] ?? null)
                ->setLocale($localeTransfer);

            $configurableBundleTemplateSlotTransfer->addTranslation($configurableBundleTemplateSlotTranslationTransfer);
        }

        return $configurableBundleTemplateSlotTransfer;
    }

    /**
     * @param string $translationKey
     * @param array $localeTransfers
     *
     * @return string[]
     */
    protected function getTranslationsByLocales(string $translationKey, array $localeTransfers): array
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

    /**
     * @param array $options
     *
     * @return array
     */
    protected function expandOptionsWithDataProviderExpanderPlugins(array $options): array
    {
        foreach ($this->configurableBundleTemplateSlotEditFormDataProviderExpanderPlugins as $configurableBundleTemplateSlotEditFormDataProviderExpanderPlugin) {
            $options = $configurableBundleTemplateSlotEditFormDataProviderExpanderPlugin->expandOptions($options);
        }

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer
     */
    protected function expandDataWithDataProviderExpanderPlugins(
        ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
    ): ConfigurableBundleTemplateSlotEditFormTransfer {
        foreach ($this->configurableBundleTemplateSlotEditFormDataProviderExpanderPlugins as $configurableBundleTemplateSlotEditFormDataProviderExpanderPlugin) {
            $configurableBundleTemplateSlotEditFormTransfer = $configurableBundleTemplateSlotEditFormDataProviderExpanderPlugin->expandData($configurableBundleTemplateSlotEditFormTransfer);
        }

        return $configurableBundleTemplateSlotEditFormTransfer;
    }
}
