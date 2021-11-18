<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

class MerchantRelationshipSalesOrderThresholdToGlossaryFacadeBridge implements MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface
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
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function hasTranslation($keyName, ?LocaleTransfer $localeTransfer = null): bool
    {
        return $this->glossaryFacade->hasTranslation($keyName, $localeTransfer);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation($keyName, LocaleTransfer $localeTransfer): TranslationTransfer
    {
        return $this->glossaryFacade->getTranslation($keyName, $localeTransfer);
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function deleteKey($keyName): bool
    {
        return $this->glossaryFacade->deleteKey($keyName);
    }

    /**
     * @param \Generated\Shared\Transfer\KeyTranslationTransfer $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer): bool
    {
        return $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);
    }
}
