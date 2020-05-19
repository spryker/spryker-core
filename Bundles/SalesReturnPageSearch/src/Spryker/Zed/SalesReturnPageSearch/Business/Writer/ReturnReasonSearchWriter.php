<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Business\Writer;

use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonPageSearchTransfer;
use Generated\Shared\Transfer\ReturnReasonTransfer;
use Spryker\Zed\SalesReturnPageSearch\Business\Mapper\ReturnReasonPageSearchMapperInterface;
use Spryker\Zed\SalesReturnPageSearch\Business\Reader\GlossaryReaderInterface;
use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToSalesReturnFacadeInterface;
use Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface;
use Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchRepositoryInterface;

class ReturnReasonSearchWriter
{
    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToSalesReturnFacadeInterface
     */
    protected $salesReturnFacade;

    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Business\Reader\GlossaryReaderInterface
     */
    protected $glossaryReader;

    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Business\Mapper\ReturnReasonPageSearchMapperInterface
     */
    protected $returnReasonPageSearchMapper;

    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToSalesReturnFacadeInterface $salesReturnFacade
     * @param \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\SalesReturnPageSearch\Business\Reader\GlossaryReaderInterface $glossaryReader
     * @param \Spryker\Zed\SalesReturnPageSearch\Business\Mapper\ReturnReasonPageSearchMapperInterface $returnReasonPageSearchMapper
     * @param \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchRepositoryInterface $repository
     * @param \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface $entityManager
     */
    public function __construct(
        SalesReturnPageSearchToSalesReturnFacadeInterface $salesReturnFacade,
        SalesReturnPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        GlossaryReaderInterface $glossaryReader,
        ReturnReasonPageSearchMapperInterface $returnReasonPageSearchMapper,
        SalesReturnPageSearchRepositoryInterface $repository,
        SalesReturnPageSearchEntityManagerInterface $entityManager
    ) {
        $this->salesReturnFacade = $salesReturnFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->glossaryReader = $glossaryReader;
        $this->returnReasonPageSearchMapper = $returnReasonPageSearchMapper;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByReturnReasonEvents(array $eventTransfers): void
    {
        $returnReasonIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->writeCollectionByReturnReasonIds($returnReasonIds);
    }

    /**
     * @param int[] $returnReasonIds
     *
     * @return void
     */
    protected function writeCollectionByReturnReasonIds(array $returnReasonIds): void
    {
        $returnReasonCollectionTransfer = $this->salesReturnFacade->getReturnReasons(
            (new ReturnReasonFilterTransfer())->setReturnReasonIds($returnReasonIds)
        );

        if (!$returnReasonCollectionTransfer->getReturnReasons()->count()) {
            $this->entityManager->deleteReturnReasonSearchByReturnReasonIds($returnReasonIds);

            return;
        }

        $returnReasonTransfers = $returnReasonCollectionTransfer->getReturnReasons()->getArrayCopy();
        $returnReasonTranslations = $this->glossaryReader->getReturnReasonTranslations($returnReasonTransfers);

        $returnReasonPageSearchTransfers = $this->indexReturnReasonPageSearchTransfersByIdReturnReasonAndLocaleName(
            $this->repository->getReturnReasonPageSearchTransfersByReturnReasonIds($returnReasonIds)
        );

        $this->writeCollection(
            $returnReasonTransfers,
            $returnReasonPageSearchTransfers,
            $returnReasonTranslations
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer[] $returnReasonTransfers
     * @param \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer[][][] $returnReasonPageSearchTransfers
     * @param string[][] $returnReasonTranslations
     *
     * @return void
     */
    protected function writeCollection(
        array $returnReasonTransfers,
        array $returnReasonPageSearchTransfers,
        array $returnReasonTranslations
    ): void {
        $localeTransfers = $this->glossaryReader->getLocaleCollection();

        foreach ($returnReasonTransfers as $returnReasonTransfer) {
            $this->writeCollectionPerLocale(
                $returnReasonTransfer,
                $returnReasonPageSearchTransfers,
                $returnReasonTranslations,
                $localeTransfers
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer $returnReasonTransfer
     * @param \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer[][][] $returnReasonPageSearchTransfers
     * @param string[][] $returnReasonTranslations
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return void
     */
    protected function writeCollectionPerLocale(
        ReturnReasonTransfer $returnReasonTransfer,
        array $returnReasonPageSearchTransfers,
        array $returnReasonTranslations,
        array $localeTransfers
    ): void {
        $idSalesReturnReason = $returnReasonTransfer->getIdSalesReturnReason();

        foreach ($localeTransfers as $localeTransfer) {
            $localeName = $localeTransfer->getLocaleName();
            $returnReasonPageSearchTransfer = $returnReasonPageSearchTransfers[$idSalesReturnReason][$localeName] ?? new ReturnReasonPageSearchTransfer();

            $this->returnReasonPageSearchMapper->mapReturnReasonTransferToReturnReasonPageSearchTransfer(
                $returnReasonTransfer,
                $returnReasonPageSearchTransfer,
                $localeTransfer,
                $returnReasonTranslations
            );

            $this->entityManager->saveReturnReasonSearchPageSearch($returnReasonPageSearchTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer[] $returnReasonPageSearchTransfers
     *
     * @return array
     */
    protected function indexReturnReasonPageSearchTransfersByIdReturnReasonAndLocaleName(array $returnReasonPageSearchTransfers): array
    {
        $indexedReturnReasonPageSearchTransfers = [];

        foreach ($returnReasonPageSearchTransfers as $returnReasonPageSearchTransfer) {
            $idSalesReturnReason = $returnReasonPageSearchTransfer->getIdSalesReturnReason();
            $localeName = $returnReasonPageSearchTransfer->getLocale();

            $indexedReturnReasonPageSearchTransfers[$idSalesReturnReason][$localeName] = $returnReasonPageSearchTransfer;
        }

        return $indexedReturnReasonPageSearchTransfers;
    }
}
