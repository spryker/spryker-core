<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuote;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\SalesOrderAmendment\Dependency\Service\SalesOrderAmendmentToUtilEncodingServiceInterface;

class SalesOrderAmendmentQuoteMapper
{
    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Dependency\Service\SalesOrderAmendmentToUtilEncodingServiceInterface
     */
    protected SalesOrderAmendmentToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Dependency\Service\SalesOrderAmendmentToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(SalesOrderAmendmentToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuote> $salesOrderAmendmentQuoteEntities
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer $salesOrderAmendmentQuoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer
     */
    public function mapSalesOrderAmendmentQuoteEntitiesToSalesOrderAmendmentQuoteCollectionTransfer(
        ObjectCollection $salesOrderAmendmentQuoteEntities,
        SalesOrderAmendmentQuoteCollectionTransfer $salesOrderAmendmentQuoteCollectionTransfer
    ): SalesOrderAmendmentQuoteCollectionTransfer {
        foreach ($salesOrderAmendmentQuoteEntities as $salesOrderAmendmentQuoteEntity) {
            $salesOrderAmendmentQuoteCollectionTransfer->addSalesOrderAmendmentQuote(
                $this->mapSalesOrderAmendmentQuoteEntityToSalesOrderAmendmentQuoteTransfer(
                    $salesOrderAmendmentQuoteEntity,
                    new SalesOrderAmendmentQuoteTransfer(),
                ),
            );
        }

        return $salesOrderAmendmentQuoteCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuote $salesOrderAmendmentQuoteEntity
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer
     */
    public function mapSalesOrderAmendmentQuoteEntityToSalesOrderAmendmentQuoteTransfer(
        SpySalesOrderAmendmentQuote $salesOrderAmendmentQuoteEntity,
        SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer
    ): SalesOrderAmendmentQuoteTransfer {
        $quoteData = $this->decodeQuoteData($salesOrderAmendmentQuoteEntity);
        $quoteTransfer = (new QuoteTransfer())->fromArray($quoteData, true);

        return $salesOrderAmendmentQuoteTransfer->fromArray($salesOrderAmendmentQuoteEntity->toArray(), true)
            ->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer
     * @param \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuote $salesOrderAmendmentQuoteEntity
     *
     * @return \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuote
     */
    public function mapSalesOrderAmendmentQuoteTransferToSalesOrderAmendmentQuoteEntity(
        SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer,
        SpySalesOrderAmendmentQuote $salesOrderAmendmentQuoteEntity
    ): SpySalesOrderAmendmentQuote {
        $quoteData = $this->encodeQuoteData($salesOrderAmendmentQuoteTransfer->getQuoteOrFail());
        $salesOrderAmendmentQuoteEntity->fromArray($salesOrderAmendmentQuoteTransfer->modifiedToArray());
        $salesOrderAmendmentQuoteEntity->setQuoteData($quoteData);

        return $salesOrderAmendmentQuoteEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function encodeQuoteData(QuoteTransfer $quoteTransfer): string
    {
        return $this->utilEncodingService->encodeJson($quoteTransfer->toArray()) ?? '';
    }

    /**
     * @param \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuote $salesOrderAmendmentQuoteEntity
     *
     * @return array<mixed>
     */
    protected function decodeQuoteData(SpySalesOrderAmendmentQuote $salesOrderAmendmentQuoteEntity): array
    {
        return $this->utilEncodingService->decodeJson($salesOrderAmendmentQuoteEntity->getQuoteData(), true) ?? [];
    }
}
