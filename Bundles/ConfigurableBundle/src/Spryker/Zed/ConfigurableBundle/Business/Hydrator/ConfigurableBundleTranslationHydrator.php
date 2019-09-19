<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Hydrator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;

class ConfigurableBundleTranslationHydrator implements ConfigurableBundleTranslationHydratorInterface
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
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function hydrateConfigurableBundleTemplateWithTranslationForCurrentLocale(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateTransfer->requireName();

        $translation = $this->glossaryFacade->translate($configurableBundleTemplateTransfer->getName());
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
    public function hydrateConfigurableBundleTemplateSlotWithTranslationForCurrentLocale(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer->requireName();

        $translation = $this->glossaryFacade->translate($configurableBundleTemplateSlotTransfer->getName());
        $configurableBundleTemplateSlotTransfer->addTranslation(
            (new ConfigurableBundleTemplateSlotTranslationTransfer())->setName($translation)
        );

        return $configurableBundleTemplateSlotTransfer;
    }
}
