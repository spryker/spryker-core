<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface;

class MerchantRelationshipMinimumOrderValueTranslationWriter implements MerchantRelationshipMinimumOrderValueTranslationWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function saveLocalizedMessages(MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): MerchantRelationshipMinimumOrderValueTransfer
    {
        $keyTranslationTransfer = $this->createKeyTranslationTransfer(
            $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue(),
            $this->createTranslationsLocaleMap($merchantRelationshipMinimumOrderValueTransfer->getLocalizedMessages())
        );

        $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);

        return $merchantRelationshipMinimumOrderValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer[] $minimumOrderValueLocalizedMessageTransfers
     *
     * @return string[]
     */
    protected function createTranslationsLocaleMap(array $minimumOrderValueLocalizedMessageTransfers): array
    {
        $translationsByLocale = [];
        foreach ($minimumOrderValueLocalizedMessageTransfers as $minimumOrderValueLocalizedMessageTransfer) {
            $translationsByLocale[$minimumOrderValueLocalizedMessageTransfer->getLocaleCode()] = $minimumOrderValueLocalizedMessageTransfer->getMessage();
        }

        return $translationsByLocale;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     * @param string[] $translationsByLocale
     *
     * @return \Generated\Shared\Transfer\KeyTranslationTransfer
     */
    protected function createKeyTranslationTransfer(MinimumOrderValueTransfer $minimumOrderValueTransfer, array $translationsByLocale): KeyTranslationTransfer
    {
        return (new KeyTranslationTransfer())
            ->setGlossaryKey($minimumOrderValueTransfer->getMessageGlossaryKey())
            ->setLocales($translationsByLocale);
    }
}
