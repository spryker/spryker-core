<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface;
use Traversable;

class MinimumOrderValueTranslationWriter implements MinimumOrderValueTranslationWriterInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        MinimumOrderValueToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function saveLocalizedMessages(GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer): GlobalMinimumOrderValueTransfer
    {
        $keyTranslationTransfer = $this->createKeyTranslationTransfer(
            $globalMinimumOrderValueTransfer->getMinimumOrderValue(),
            $this->createTranslationsLocaleMap($globalMinimumOrderValueTransfer->getLocalizedMessages())
        );

        $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);

        return $globalMinimumOrderValueTransfer;
    }

    /**
     * @param \Traversable|\Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer[] $minimumOrderValueLocalizedMessageTransfers
     *
     * @return string[]
     */
    protected function createTranslationsLocaleMap(Traversable $minimumOrderValueLocalizedMessageTransfers): array
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
