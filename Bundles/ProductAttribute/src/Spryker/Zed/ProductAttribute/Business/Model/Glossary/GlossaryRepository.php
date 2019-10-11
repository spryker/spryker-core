<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Glossary;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface;

class GlossaryRepository implements GlossaryRepositoryInterface
{
    /**
     * @var array
     */
    protected static $glossaryMap;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface $glossaryFacade
     */
    public function __construct(ProductAttributeToGlossaryInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return string
     */
    public function getTranslationByKeyNameAndLocaleTransfer($keyName, LocaleTransfer $locale): string
    {
        if (!isset(static::$glossaryMap[$locale->getIdLocale()][$keyName])) {
            $this->loadTranslations([$keyName], [$locale->getIdLocale()]);
        }

        return static::$glossaryMap[$locale->getIdLocale()][$keyName];
    }

    /**
     * @param string[] $keyNames
     * @param string[] $localeNames
     *
     * @return void
     */
    public function loadTranslations(array $keyNames, array $localeNames): void
    {
        $translations = $this->glossaryFacade->getTranslations($keyNames, $localeNames);
        foreach ($translations as $translation) {
            static::$glossaryMap[$translation->getFkLocale()][$translation->getGlossaryKey()] = $translation->getValue();
        }
    }
}
