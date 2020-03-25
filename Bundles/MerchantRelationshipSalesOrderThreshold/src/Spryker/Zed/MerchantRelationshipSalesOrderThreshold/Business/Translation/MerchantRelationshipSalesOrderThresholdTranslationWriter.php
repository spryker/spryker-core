<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface;
use Traversable;

class MerchantRelationshipSalesOrderThresholdTranslationWriter implements MerchantRelationshipSalesOrderThresholdTranslationWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function saveLocalizedMessages(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $keyTranslationTransfer = $this->createKeyTranslationTransfer(
            $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue(),
            $this->createTranslationsLocaleMap($merchantRelationshipSalesOrderThresholdTransfer->getLocalizedMessages())
        );

        $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return void
     */
    public function deleteLocalizedMessages(MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): void
    {
        foreach ($merchantRelationshipSalesOrderThresholdTransfer->getLocalizedMessages() as $localizedMessageTransfer) {
            $localizedMessageTransfer->setMessage(null);
        }

        $this->saveLocalizedMessages($merchantRelationshipSalesOrderThresholdTransfer);
        $this->glossaryFacade->deleteKey(
            $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getMessageGlossaryKey()
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
    protected function createKeyTranslationTransfer(
        SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer,
        array $translationsByLocale
    ): KeyTranslationTransfer {
        return (new KeyTranslationTransfer())
            ->setGlossaryKey($salesOrderThresholdValueTransfer->getMessageGlossaryKey())
            ->setLocales($translationsByLocale);
    }
}
