<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
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
    public function createConfigurableBundleTemplateTranslations(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): void {
        $this->glossaryFacade->createKey($configurableBundleTemplateTransfer->getTranslationKey());

        foreach ($configurableBundleTemplateTransfer->getTranslations() as $configurableBundleTemplateTranslationTransfer) {
            $this->glossaryFacade->createTranslation(
                $configurableBundleTemplateTransfer->getTranslationKey(),
                $configurableBundleTemplateTranslationTransfer->getLocale(),
                $configurableBundleTemplateTranslationTransfer->getName()
            );
        }
    }
}
