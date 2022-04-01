<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToLocaleFacadeInterface;

class SalesOrderThresholdTranslationHydrator implements SalesOrderThresholdTranslationHydratorInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        SalesOrderThresholdToGlossaryFacadeInterface $glossaryFacade,
        SalesOrderThresholdToLocaleFacadeInterface $localeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer> $salesOrderThresholdTransfers
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer>
     */
    public function expandWithLocalizedMessagesCollection(array $salesOrderThresholdTransfers): array
    {
        $glossaryKeys = $this->getGlossaryKeys($salesOrderThresholdTransfers);

        if (!$glossaryKeys) {
            return $salesOrderThresholdTransfers;
        }

        $glossaryKeyTransfers = $this->glossaryFacade->getGlossaryKeyTransfersByGlossaryKeys($glossaryKeys);
        $availableLocaleTransfers = $this->localeFacade->getLocaleCollection();
        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers($glossaryKeys, $availableLocaleTransfers);
        $translationTransfers = $this->getTranslationTransfersGroupedByIdLocale($translationTransfers, $glossaryKeyTransfers);

        $salesOrderThresholdTransfers = $this->expandSalesOrderThresholdTransferWithLocalizedMessagesCollection(
            $salesOrderThresholdTransfers,
            $translationTransfers,
            $availableLocaleTransfers,
        );

        return $salesOrderThresholdTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     * @param array<int|string, array<string, \Generated\Shared\Transfer\TranslationTransfer>> $translationTransfers
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $availableLocaleTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function extendSalesOrderThresholdTransferWithLocalizedMessages(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer,
        array $translationTransfers,
        array $availableLocaleTransfers
    ): SalesOrderThresholdTransfer {
        $localizedMessages = new ArrayObject();
        $messageGlossaryKey = $salesOrderThresholdTransfer->getSalesOrderThresholdValueOrFail()->getMessageGlossaryKeyOrFail();

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $translationTransfer = $translationTransfers[$localeTransfer->getIdLocale()][$messageGlossaryKey] ?? null;
            $salesOrderThresholdLocalizedMessageTransfer = $this->createLocalizedMessage($localeTransfer, $translationTransfer);

            $localizedMessages->append($salesOrderThresholdLocalizedMessageTransfer);
        }
        $salesOrderThresholdTransfer->setLocalizedMessages($localizedMessages);

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer> $salesOrderThresholdTransfers
     * @param array<int|string, array<string, \Generated\Shared\Transfer\TranslationTransfer>> $translationTransfers
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $availableLocaleTransfers
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer>
     */
    protected function expandSalesOrderThresholdTransferWithLocalizedMessagesCollection(
        array $salesOrderThresholdTransfers,
        array $translationTransfers,
        array $availableLocaleTransfers
    ): array {
        foreach ($salesOrderThresholdTransfers as $salesOrderThresholdTransfer) {
            $salesOrderThresholdTransfer = $this->extendSalesOrderThresholdTransferWithLocalizedMessages($salesOrderThresholdTransfer, $translationTransfers, $availableLocaleTransfers);
        }

        return $salesOrderThresholdTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\TranslationTransfer> $translationTransfers
     * @param array<\Generated\Shared\Transfer\GlossaryKeyTransfer> $glossaryKeyTransfers
     *
     * @return array<int|string, array<string, \Generated\Shared\Transfer\TranslationTransfer>>
     */
    protected function getTranslationTransfersGroupedByIdLocale(
        array $translationTransfers,
        array $glossaryKeyTransfers
    ): array {
        $groupedTranslationTransfers = [];
        $glossaryKeyTransfers = $this->getGlossaryKeyTransfersIndexedByIdGlossaryKey($glossaryKeyTransfers);

        foreach ($translationTransfers as $translationTransfer) {
            $key = $glossaryKeyTransfers[$translationTransfer->getFkGlossaryKeyOrFail()]->getKeyOrFail();
            $groupedTranslationTransfers[$translationTransfer->getFkLocaleOrFail()][$key] = $translationTransfer;
        }

        return $groupedTranslationTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\GlossaryKeyTransfer> $glossaryKeyTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\GlossaryKeyTransfer>
     */
    protected function getGlossaryKeyTransfersIndexedByIdGlossaryKey(array $glossaryKeyTransfers): array
    {
        $indexedGlossaryKeyTransfers = [];

        foreach ($glossaryKeyTransfers as $glossaryKeyTransfer) {
            $indexedGlossaryKeyTransfers[$glossaryKeyTransfer->getIdGlossaryKeyOrFail()] = $glossaryKeyTransfer;
        }

        return $indexedGlossaryKeyTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\TranslationTransfer|null $translationTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer
     */
    protected function createLocalizedMessage(
        LocaleTransfer $localeTransfer,
        ?TranslationTransfer $translationTransfer
    ): SalesOrderThresholdLocalizedMessageTransfer {
        $message = $translationTransfer ? $translationTransfer->getValue() : null;
        $salesOrderThresholdLocalizedMessageTransfer = (new SalesOrderThresholdLocalizedMessageTransfer())
            ->setLocaleCode($localeTransfer->getLocaleName())
            ->setMessage($message);

        return $salesOrderThresholdLocalizedMessageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return string|null
     */
    protected function getGlossaryKey(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): ?string
    {
        if ($salesOrderThresholdTransfer->getSalesOrderThresholdValue() === null) {
            return null;
        }

        return $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getMessageGlossaryKey() ?: null;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer> $salesOrderThresholdTransfers
     *
     * @return array<string>
     */
    protected function getGlossaryKeys(array $salesOrderThresholdTransfers): array
    {
        $glossaryKeys = [];

        foreach ($salesOrderThresholdTransfers as $salesOrderThresholdTransfer) {
            $glossaryKeys[] = $this->getGlossaryKey($salesOrderThresholdTransfer);
        }

        return $glossaryKeys;
    }
}
