<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
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
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function saveLocalizedMessages(MinimumOrderValueTransfer $minimumOrderValueTransfer): MinimumOrderValueTransfer
    {
        $keyTranslationTransfer = $this->createKeyTranslationTransfer(
            $minimumOrderValueTransfer->getThreshold(),
            $this->createTranslationsLocaleMap($minimumOrderValueTransfer->getLocalizedMessages())
        );

        $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);

        return $minimumOrderValueTransfer;
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
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     * @param string[] $translationsByLocale
     *
     * @return \Generated\Shared\Transfer\KeyTranslationTransfer
     */
    protected function createKeyTranslationTransfer(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer, array $translationsByLocale): KeyTranslationTransfer
    {
        return (new KeyTranslationTransfer())
            ->setGlossaryKey($minimumOrderValueThresholdTransfer->getThresholdNotMetMessageGlossaryKey())
            ->setLocales($translationsByLocale);
    }
}
