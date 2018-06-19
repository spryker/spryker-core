<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

class ProductPackagingUnitToGlossaryBridge implements ProductPackagingUnitToGlossaryInterface
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
     * {@inheritdoc}
     *
     * @return bool
     */
    public function hasTranslation(string $keyName, ?LocaleTransfer $localeTransfer = null): bool
    {
        return $this->glossaryFacade->hasTranslation($keyName, $localeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation(string $keyName, LocaleTransfer $localeTransfer): TranslationTransfer
    {
        return $this->glossaryFacade->getTranslation($keyName, $localeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $key
     * @param string $value
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function saveAndTouchTranslation(string $key, string $value, LocaleTransfer $localeTransfer): TranslationTransfer
    {
        if (!$this->hasTranslation($key, $localeTransfer)) {
            return $this->glossaryFacade->createAndTouchTranslation($key, $localeTransfer, $value);
        } else {
            return $this->glossaryFacade->updateAndTouchTranslation($key, $localeTransfer, $value);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey(string $keyName): bool
    {
        return $this->glossaryFacade->hasKey($keyName);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $keyName
     *
     * @return int
     */
    public function createKey(string $keyName): int
    {
        return $this->glossaryFacade->createKey($keyName);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function deleteKey(string $keyName): bool
    {
        return $this->glossaryFacade->deleteKey($keyName);
    }
}
