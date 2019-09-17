<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Dependency\Facade;

class ConfigurableBundleGuiToGlossaryFacadeBridge implements ConfigurableBundleGuiToGlossaryFacadeInterface
{
    /**
     * @var \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface $glossaryFacade
     */
    public function __construct($glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param string $glossaryKey
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer[]
     */
    public function getTranslationsByGlossaryKeyAndLocales(string $glossaryKey, array $localeTransfers): array
    {
        return $this->glossaryFacade->getTranslationsByGlossaryKeyAndLocales($glossaryKey, $localeTransfers);
    }
}
