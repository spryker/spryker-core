<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Business\Reader;

use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToGlossaryFacadeInterface;
use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToLocaleFacadeInterface;

class GlossaryReader implements GlossaryReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        SalesReturnPageSearchToGlossaryFacadeInterface $glossaryFacade,
        SalesReturnPageSearchToLocaleFacadeInterface $localeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer[] $returnReasonTransfers
     *
     * @return string[][]
     */
    public function getReturnReasonTranslations(array $returnReasonTransfers): array
    {
        $glossaryKeys = $this->extractGlossaryKeysFromReturnReasonTransfers($returnReasonTransfers);

        $glossaryKeyTransfers = $this->indexGlossaryKeyTransfersByIdGlossaryKey(
            $this->glossaryFacade->getGlossaryKeyTransfersByGlossaryKeys($glossaryKeys)
        );

        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers(
            $glossaryKeys,
            $this->localeFacade->getLocaleCollection()
        );

        return $this->mapReturnReasonTranslations($translationTransfers, $glossaryKeyTransfers);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection(): array
    {
        return $this->localeFacade->getLocaleCollection();
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer[] $translationTransfers
     * @param \Generated\Shared\Transfer\GlossaryKeyTransfer[] $glossaryKeyTransfers
     *
     * @return string[][]
     */
    protected function mapReturnReasonTranslations(array $translationTransfers, array $glossaryKeyTransfers): array
    {
        $returnReasonTranslations = [];

        foreach ($translationTransfers as $translationTransfer) {
            $glossaryKeyTransfer = $glossaryKeyTransfers[$translationTransfer->getFkGlossaryKey()] ?? null;

            if ($glossaryKeyTransfer) {
                $returnReasonTranslations[$glossaryKeyTransfer->getKey()][$translationTransfer->getFkLocale()] = $translationTransfer->getValue();
            }
        }

        return $returnReasonTranslations;
    }

    /**
     * @param \Generated\Shared\Transfer\GlossaryKeyTransfer[] $glossaryKeyTransfers
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    protected function indexGlossaryKeyTransfersByIdGlossaryKey(array $glossaryKeyTransfers): array
    {
        $indexedGlossaryKeyTransfers = [];

        foreach ($glossaryKeyTransfers as $glossaryKeyTransfer) {
            $indexedGlossaryKeyTransfers[$glossaryKeyTransfer->getIdGlossaryKey()] = $glossaryKeyTransfer;
        }

        return $indexedGlossaryKeyTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer[] $returnReasonTransfers
     *
     * @return string[]
     */
    protected function extractGlossaryKeysFromReturnReasonTransfers(array $returnReasonTransfers): array
    {
        $glossaryKeys = [];

        foreach ($returnReasonTransfers as $returnReasonTransfer) {
            $glossaryKeys[] = $returnReasonTransfer->getGlossaryKeyReason();
        }

        return $glossaryKeys;
    }
}
