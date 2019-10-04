<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

class MerchantProfileGuiToGlossaryFacadeBridge implements MerchantProfileGuiToGlossaryFacadeInterface
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
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, ?LocaleTransfer $locale = null): bool
    {
        return $this->glossaryFacade->hasTranslation($keyName, $locale);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation($keyName, LocaleTransfer $locale): TranslationTransfer
    {
        return $this->glossaryFacade->getTranslation($keyName, $locale);
    }
}
