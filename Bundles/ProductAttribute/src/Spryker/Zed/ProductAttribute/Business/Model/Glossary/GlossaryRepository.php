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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getTranslationByKeyNameAndLocaleTransfer($keyName, LocaleTransfer $localeTransfer): string
    {
        if (!isset(static::$glossaryMap[$localeTransfer->getIdLocale()][$keyName])) {
            $this->loadTranslations([$keyName], [$localeTransfer]);
        }

        return static::$glossaryMap[$localeTransfer->getIdLocale()][$keyName];
    }

    /**
     * @param string[] $keyNames
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return void
     */
    public function loadTranslations(array $keyNames, array $localeTransfers): void
    {
        $translations = $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocales($keyNames, $localeTransfers);
        foreach ($translations as $translation) {
            static::$glossaryMap[$translation->getFkLocale()][$translation->getGlossaryKey()] = $translation->getValue();
        }
    }
}
