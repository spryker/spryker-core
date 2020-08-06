<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business\Reader;

use Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToGlossaryFacadeInterface;
use Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToLocaleFacadeInterface;

class GlossaryReader implements GlossaryReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        SalesReturnSearchToGlossaryFacadeInterface $glossaryFacade,
        SalesReturnSearchToLocaleFacadeInterface $localeFacade
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
