<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Translation;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToLocaleFacadeInterface;

class SalesOrderThresholdTranslationReader implements SalesOrderThresholdTranslationReaderInterface
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
        $translationTransfers = $this->glossaryFacade->findTranslationsByGlossaryKeyAndLocales($glossaryKey, $availableLocaleTransfers);
        $localizedMessages = new ArrayObject();

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $translationTransfer = $this->findTranslationTransferByLocaleId($localeTransfer->getIdLocale(), $translationTransfers);
            $salesOrderThresholdLocalizedMessageTransfer = $this->createLocalizedMessage($localeTransfer, $translationTransfer);

            $localizedMessages->append($salesOrderThresholdLocalizedMessageTransfer);
        }
        $salesOrderThresholdTransfer->setLocalizedMessages($localizedMessages);

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param int $localeId
     * @param \Generated\Shared\Transfer\TranslationTransfer[] $translations
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer|null
     */
    protected function findTranslationTransferByLocaleId(int $localeId, array $translations): ?TranslationTransfer
    {
        foreach ($translations as $translation) {
            if ($translation->getFkLocale() === $localeId) {
                return $translation;
            }
        }

        return null;
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
