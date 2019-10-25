<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

class ConfigurableBundleToGlossaryFacadeBridge implements ConfigurableBundleToGlossaryFacadeInterface
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation(string $keyName, LocaleTransfer $locale, string $value, bool $isActive = true): TranslationTransfer
    {
        return $this->glossaryFacade->createTranslation($keyName, $locale, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation(string $keyName, LocaleTransfer $locale, string $value, bool $isActive = true): TranslationTransfer
    {
        return $this->glossaryFacade->updateTranslation($keyName, $locale, $value, $isActive);
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
     *
     * @return bool
     */
    public function hasKey(string $keyName): bool
    {
        return $this->glossaryFacade->hasKey($keyName);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation(string $keyName, ?LocaleTransfer $locale = null): bool
    {
        return $this->glossaryFacade->hasTranslation($keyName, $locale);
    }

    /**
     * @param string $keyName
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate(string $keyName, array $data = [], ?LocaleTransfer $localeTransfer = null): string
    {
        return $this->glossaryFacade->translate($keyName, $data, $localeTransfer);
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

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation(string $keyName, LocaleTransfer $locale): TranslationTransfer
    {
        return $this->glossaryFacade->getTranslation($keyName, $locale);
    }
}
