<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Translation;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface;
use Traversable;

class SalesOrderThresholdTranslationWriter implements SalesOrderThresholdTranslationWriterInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        SalesOrderThresholdToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function saveLocalizedMessages(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): SalesOrderThresholdTransfer
    {
        $keyTranslationTransfer = $this->createKeyTranslationTransfer(
            $salesOrderThresholdTransfer->getSalesOrderThresholdValue(),
            $this->createTranslationsLocaleMap($salesOrderThresholdTransfer->getLocalizedMessages())
        );

        $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return void
     */
    public function deleteLocalizedMessages(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): void
    {
        foreach ($salesOrderThresholdTransfer->getLocalizedMessages() as $localizedMessageTransfer) {
            $localizedMessageTransfer->setMessage(null);
        }

        $this->saveLocalizedMessages($salesOrderThresholdTransfer);
        $this->glossaryFacade->deleteKey(
            $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getMessageGlossaryKey()
        );
    }

    /**
     * @param \Traversable|\Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer[] $salesOrderThresholdLocalizedMessageTransfers
     *
     * @return string[]
     */
    protected function createTranslationsLocaleMap(Traversable $salesOrderThresholdLocalizedMessageTransfers): array
    {
        $translationsByLocale = [];
        foreach ($salesOrderThresholdLocalizedMessageTransfers as $salesOrderThresholdLocalizedMessageTransfer) {
            $translationsByLocale[$salesOrderThresholdLocalizedMessageTransfer->getLocaleCode()] = $salesOrderThresholdLocalizedMessageTransfer->getMessage();
        }

        return $translationsByLocale;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     * @param string[] $translationsByLocale
     *
     * @return \Generated\Shared\Transfer\KeyTranslationTransfer
     */
    protected function createKeyTranslationTransfer(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer, array $translationsByLocale): KeyTranslationTransfer
    {
        return (new KeyTranslationTransfer())
            ->setGlossaryKey($salesOrderThresholdValueTransfer->getMessageGlossaryKey())
            ->setLocales($translationsByLocale);
    }
}
