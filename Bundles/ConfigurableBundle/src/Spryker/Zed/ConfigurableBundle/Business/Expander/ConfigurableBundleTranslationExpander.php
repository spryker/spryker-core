<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
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
     * @param \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function expandConfigurableBundleTemplateWithTranslations(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ArrayObject $localeTransfers
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateTransfer->requireName();

        if (!$localeTransfers->count()) {
            $localeTransfers->exchangeArray($this->localeFacade->getLocaleCollection());
        }

        $configurableBundleTemplateTranslationTransfers = $this->getConfigurableBundleTemplateTranslations(
            $this->getTranslations($configurableBundleTemplateTransfer->getName(), $localeTransfers),
            $localeTransfers
        );

        return $configurableBundleTemplateTransfer->setTranslations($configurableBundleTemplateTranslationTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function expandConfigurableBundleTemplateSlotWithTranslations(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        ArrayObject $localeTransfers
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer->requireName();

        if (!$localeTransfers->count()) {
            $localeTransfers->exchangeArray($this->localeFacade->getLocaleCollection());
        }

        $configurableBundleTemplateSlotTranslationTransfers = $this->getConfigurableBundleTemplateSlotTranslations(
            $this->getTranslations($configurableBundleTemplateSlotTransfer->getName(), $localeTransfers),
            $localeTransfers
        );

        return $configurableBundleTemplateSlotTransfer->setTranslations($configurableBundleTemplateSlotTranslationTransfers);
    }

    /**
     * @param string $translationKey
     * @param \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer[]
     */
    protected function getTranslations(string $translationKey, ArrayObject $localeTransfers): array
    {
        if ($localeTransfers->count() === 1) {
            return [$this->getSingleLocaleTranslation($translationKey, $localeTransfers->getIterator()->current())];
        }

        return $this->glossaryFacade->getTranslationsByGlossaryKeyAndLocales($translationKey, $localeTransfers->getArrayCopy());
    }

    /**
     * @param string $translationKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function getSingleLocaleTranslation(string $translationKey, LocaleTransfer $localeTransfer): TranslationTransfer
    {
        if ($this->glossaryFacade->hasTranslation($translationKey, $localeTransfer)) {
            return $this->glossaryFacade->getTranslation($translationKey, $localeTransfer);
        }

        return $this->getFallbackTranslation($translationKey);
    }

    /**
     * @param string $translationKey
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function getFallbackTranslation(string $translationKey): TranslationTransfer
    {
        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeyAndLocales(
            $translationKey,
            $this->localeFacade->getLocaleCollection()
        );

        if ($translationTransfers) {
            return reset($translationTransfers);
        }

        return (new TranslationTransfer())
            ->setValue($translationKey);
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer[] $translationTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer[]
     */
    protected function getConfigurableBundleTemplateTranslations(array $translationTransfers, ArrayObject $localeTransfers): ArrayObject
    {
        $configurableBundleTemplateTranslationTransfers = new ArrayObject();
        $translationTransfers = $this->getTranslationTransfersIndexedByIdLocale($translationTransfers);

        foreach ($localeTransfers as $localeTransfer) {
            $configurableBundleTemplateTranslationTransfer = (new ConfigurableBundleTemplateTranslationTransfer())
                ->setLocale($localeTransfer);

            if (isset($translationTransfers[$localeTransfer->getIdLocale()])) {
                $configurableBundleTemplateTranslationTransfer->setName($translationTransfers[$localeTransfer->getIdLocale()]->getValue());
            }

            $configurableBundleTemplateTranslationTransfers->append($configurableBundleTemplateTranslationTransfer);
        }

        return $configurableBundleTemplateTranslationTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer[] $translationTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer[]
     */
    protected function getConfigurableBundleTemplateSlotTranslations(array $translationTransfers, ArrayObject $localeTransfers): ArrayObject
    {
        $configurableBundleTemplateSlotTranslationTransfers = new ArrayObject();
        $translationTransfers = $this->getTranslationTransfersIndexedByIdLocale($translationTransfers);

        foreach ($localeTransfers as $localeTransfer) {
            $configurableBundleTemplateSlotTranslationTransfer = (new ConfigurableBundleTemplateSlotTranslationTransfer())
                ->setLocale($localeTransfer);

            if (isset($translationTransfers[$localeTransfer->getIdLocale()])) {
                $configurableBundleTemplateSlotTranslationTransfer->setName($translationTransfers[$localeTransfer->getIdLocale()]->getValue());
            }

            $configurableBundleTemplateSlotTranslationTransfers->append($configurableBundleTemplateSlotTranslationTransfer);
        }

        return $configurableBundleTemplateSlotTranslationTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer[] $translationTransfers
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer[]
     */
    protected function getTranslationTransfersIndexedByIdLocale(array $translationTransfers): array
    {
        $indexedTranslationTransfers = [];

        foreach ($translationTransfers as $translationTransfer) {
            $indexedTranslationTransfers[$translationTransfer->getFkLocale()] = $translationTransfer;
        }

        return $indexedTranslationTransfers;
    }
}
