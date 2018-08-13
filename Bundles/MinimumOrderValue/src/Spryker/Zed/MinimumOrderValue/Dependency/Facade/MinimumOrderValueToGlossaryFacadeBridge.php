<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

class MinimumOrderValueToGlossaryFacadeBridge implements MinimumOrderValueToGlossaryFacadeInterface
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
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey(string $keyName): bool
    {
        return $this->glossaryFacade->hasKey($keyName);
    }

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function createKey(string $keyName): int
    {
        return $this->glossaryFacade->createKey($keyName);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return bool
     */
    public function hasTranslation(string $keyName, LocaleTransfer $localeTransfer): bool
    {
        return $this->glossaryFacade->hasTranslation($keyName, $localeTransfer);
    }

    /**
     * @param string $keyName
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation(string $keyName, LocaleTransfer $localeTransfer): TranslationTransfer
    {
        return $this->glossaryFacade->getTranslation($keyName, $localeTransfer);
    }

    /**
     * @param string $keyName
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\LocaleTransfer $localeTransfer
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation(
        string $keyName,
        LocaleTransfer $localeTransfer,
        string $value
    ): TranslationTransfer {
        return $this->glossaryFacade->createTranslation($keyName, $localeTransfer, $value);
    }

    /**
     * @param string $keyName
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\LocaleTransfer $localeTransfer
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation(
        string $keyName,
        LocaleTransfer $localeTransfer,
        string $value
    ): TranslationTransfer {
        return $this->glossaryFacade->updateTranslation($keyName, $localeTransfer, $value);
    }
}
