<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Dependency\Facade;

class ProductSetPageSearchToGlossaryFacadeBridge implements ProductSetPageSearchToGlossaryFacadeInterface
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
     * @param array<string> $glossaryKeys
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     *
     * @return array<\Generated\Shared\Transfer\TranslationTransfer>
     */
    public function getTranslationsByGlossaryKeysAndLocaleTransfers(array $glossaryKeys, array $localeTransfers): array
    {
        return $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers($glossaryKeys, $localeTransfers);
    }
}
