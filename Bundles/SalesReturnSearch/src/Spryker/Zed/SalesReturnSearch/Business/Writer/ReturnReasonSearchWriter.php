<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business\Writer;

use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonSearchTransfer;
use Generated\Shared\Transfer\ReturnReasonTransfer;
use Spryker\Zed\SalesReturnSearch\Business\Mapper\ReturnReasonSearchMapperInterface;
use Spryker\Zed\SalesReturnSearch\Business\Reader\GlossaryReaderInterface;
use Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToSalesReturnFacadeInterface;
use Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchEntityManagerInterface;
use Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchRepositoryInterface;

class ReturnReasonSearchWriter implements ReturnReasonSearchWriterInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToSalesReturnFacadeInterface
     */
    protected $salesReturnFacade;

    /**
     * @var \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\SalesReturnSearch\Business\Reader\GlossaryReaderInterface
     */
    protected $glossaryReader;

    /**
     * @var \Spryker\Zed\SalesReturnSearch\Business\Mapper\ReturnReasonSearchMapperInterface
     */
    protected $returnReasonSearchMapper;

    /**
     * @var \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToSalesReturnFacadeInterface $salesReturnFacade
     * @param \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\SalesReturnSearch\Business\Reader\GlossaryReaderInterface $glossaryReader
     * @param \Spryker\Zed\SalesReturnSearch\Business\Mapper\ReturnReasonSearchMapperInterface $returnReasonSearchMapper
     * @param \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchRepositoryInterface $repository
     * @param \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchEntityManagerInterface $entityManager
     */
    public function __construct(
        SalesReturnSearchToSalesReturnFacadeInterface $salesReturnFacade,
        SalesReturnSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        GlossaryReaderInterface $glossaryReader,
        ReturnReasonSearchMapperInterface $returnReasonSearchMapper,
        SalesReturnSearchRepositoryInterface $repository,
        SalesReturnSearchEntityManagerInterface $entityManager
    ) {
        $this->salesReturnFacade = $salesReturnFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->glossaryReader = $glossaryReader;
        $this->returnReasonSearchMapper = $returnReasonSearchMapper;
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
        if (!$returnReasonIds) {
            return;
        }

        $returnReasonCollectionTransfer = $this->salesReturnFacade->getReturnReasons(
            (new ReturnReasonFilterTransfer())->setReturnReasonIds($returnReasonIds)
        );

        if (!$returnReasonCollectionTransfer->getReturnReasons()->count()) {
            $this->entityManager->deleteReturnReasonSearchByReturnReasonIds($returnReasonIds);

            return;
        }

        $returnReasonTransfers = $returnReasonCollectionTransfer->getReturnReasons()->getArrayCopy();
        $returnReasonTranslations = $this->glossaryReader->getReturnReasonTranslations($returnReasonTransfers);

        $returnReasonSearchTransfers = $this->indexReturnReasonSearchTransfersByIdReturnReasonAndLocaleName(
            $this->repository->getReturnReasonSearchTransfersByReturnReasonIds($returnReasonIds)
        );

        $this->writeCollection(
            $returnReasonTransfers,
            $returnReasonSearchTransfers,
            $returnReasonTranslations
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer[] $returnReasonTransfers
     * @param \Generated\Shared\Transfer\ReturnReasonSearchTransfer[][] $returnReasonSearchTransfers
     * @param string[][] $returnReasonTranslations
     *
     * @return void
     */
    protected function writeCollection(
        array $returnReasonTransfers,
        array $returnReasonSearchTransfers,
        array $returnReasonTranslations
    ): void {
        $localeTransfers = $this->glossaryReader->getLocaleCollection();

        foreach ($returnReasonTransfers as $returnReasonTransfer) {
            $this->writeCollectionPerLocale(
                $returnReasonTransfer,
                $returnReasonSearchTransfers,
                $returnReasonTranslations,
                $localeTransfers
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer $returnReasonTransfer
     * @param \Generated\Shared\Transfer\ReturnReasonSearchTransfer[][] $returnReasonSearchTransfers
     * @param string[][] $returnReasonTranslations
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return void
     */
    protected function writeCollectionPerLocale(
        ReturnReasonTransfer $returnReasonTransfer,
        array $returnReasonSearchTransfers,
        array $returnReasonTranslations,
        array $localeTransfers
    ): void {
        $idSalesReturnReason = $returnReasonTransfer->getIdSalesReturnReason();

        foreach ($localeTransfers as $localeTransfer) {
            $localeName = $localeTransfer->getLocaleName();
            $returnReasonSearchTransfer = $returnReasonSearchTransfers[$idSalesReturnReason][$localeName] ?? new ReturnReasonSearchTransfer();

            $this->returnReasonSearchMapper->mapReturnReasonTransferToReturnReasonSearchTransfer(
                $returnReasonTransfer,
                $returnReasonSearchTransfer,
                $localeTransfer,
                $returnReasonTranslations
            );

            $this->entityManager->saveReturnReasonSearch($returnReasonSearchTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonSearchTransfer[] $returnReasonSearchTransfers
     *
     * @return array
     */
    protected function indexReturnReasonSearchTransfersByIdReturnReasonAndLocaleName(array $returnReasonSearchTransfers): array
    {
        $indexedReturnReasonSearchTransfers = [];

        foreach ($returnReasonSearchTransfers as $returnReasonSearchTransfer) {
            $idSalesReturnReason = $returnReasonSearchTransfer->getIdSalesReturnReason();
            $localeName = $returnReasonSearchTransfer->getLocale();

            $indexedReturnReasonSearchTransfers[$idSalesReturnReason][$localeName] = $returnReasonSearchTransfer;
        }

        return $indexedReturnReasonSearchTransfers;
    }
}
