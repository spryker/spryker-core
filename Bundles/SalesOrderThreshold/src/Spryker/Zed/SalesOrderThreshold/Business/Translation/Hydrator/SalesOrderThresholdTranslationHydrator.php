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
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function hydrateLocalizedMessages(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): SalesOrderThresholdTransfer
    {
        $glossaryKey = $this->getGlossaryKey($salesOrderThresholdTransfer);
        if (!$glossaryKey) {
            return $salesOrderThresholdTransfer;
        }

        $availableLocaleTransfers = $this->localeFacade->getLocaleCollection();
        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeyAndLocales($glossaryKey, $availableLocaleTransfers);

        $salesOrderThresholdTransfer = $this
            ->extendSalesOrderThresholdTransferWithLocalizedMessages($salesOrderThresholdTransfer, $translationTransfers, $availableLocaleTransfers);

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     * @param array $translationTransfers
     * @param array $availableLocaleTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function extendSalesOrderThresholdTransferWithLocalizedMessages(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer,
        array $translationTransfers,
        array $availableLocaleTransfers
    ): SalesOrderThresholdTransfer {
        $localizedMessages = new ArrayObject();

        $indexedTranslationTransfers = $this->indexTranslationTransfersByLocaleId($translationTransfers);
        foreach ($availableLocaleTransfers as $localeTransfer) {
            $translationTransfer = $indexedTranslationTransfers[$localeTransfer->getIdLocale()] ?? null;
            $salesOrderThresholdLocalizedMessageTransfer = $this->createLocalizedMessage($localeTransfer, $translationTransfer);

            $localizedMessages->append($salesOrderThresholdLocalizedMessageTransfer);
        }
        $salesOrderThresholdTransfer->setLocalizedMessages($localizedMessages);

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer[] $translationTransfers
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer[]
     */
    protected function indexTranslationTransfersByLocaleId(array $translationTransfers): array
    {
        $indexedTranslationTransfers = [];
        foreach ($translationTransfers as $translationTransfer) {
            $indexedTranslationTransfers[$translationTransfer->getFkLocale()] = $translationTransfer;
        }

        return $indexedTranslationTransfers;
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
}
