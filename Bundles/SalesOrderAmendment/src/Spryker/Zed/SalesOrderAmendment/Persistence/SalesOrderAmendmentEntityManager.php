<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Persistence;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendment;
use Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuote;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentPersistenceFactory getFactory()
 */
class SalesOrderAmendmentEntityManager extends AbstractEntityManager implements SalesOrderAmendmentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    public function createSalesOrderAmendment(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentTransfer {
        $salesOrderAmendmentMapper = $this->getFactory()->createSalesOrderAmendmentMapper();
        $salesOrderAmendmentEntity = $salesOrderAmendmentMapper->mapSalesOrderAmendmentTransferToSalesOrderAmendmentEntity(
            $salesOrderAmendmentTransfer,
            new SpySalesOrderAmendment(),
        );

        $salesOrderAmendmentEntity->save();

        return $salesOrderAmendmentMapper->mapSalesOrderAmendmentEntityToSalesOrderAmendmentTransfer(
            $salesOrderAmendmentEntity,
            $salesOrderAmendmentTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer
     * @param array<string|array<string>> $quoteFieldsAllowedForSaving
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer
     */
    public function createSalesOrderAmendmentQuote(
        SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer,
        array $quoteFieldsAllowedForSaving
    ): SalesOrderAmendmentQuoteTransfer {
        $salesOrderAmendmentQuoteEntity = $this->getFactory()
            ->createSalesOrderAmendmentQuoteMapper()
            ->mapSalesOrderAmendmentQuoteTransferToSalesOrderAmendmentQuoteEntity(
                $salesOrderAmendmentQuoteTransfer,
                new SpySalesOrderAmendmentQuote(),
                $quoteFieldsAllowedForSaving,
            );

        $salesOrderAmendmentQuoteEntity->save();

        return $this->getFactory()
            ->createSalesOrderAmendmentQuoteMapper()
            ->mapSalesOrderAmendmentQuoteEntityToSalesOrderAmendmentQuoteTransfer(
                $salesOrderAmendmentQuoteEntity,
                $salesOrderAmendmentQuoteTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer
     * @param array<string|array<string>> $quoteFieldsAllowedForSaving
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer
     */
    public function updateSalesOrderAmendmentQuote(
        SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer,
        array $quoteFieldsAllowedForSaving
    ): SalesOrderAmendmentQuoteTransfer {
        /** @var \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuote $salesOrderAmendmentQuoteEntity */
        $salesOrderAmendmentQuoteEntity = $this->getFactory()
            ->getSalesOrderAmendmentQuoteQuery()
            ->filterByIdSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer->getIdSalesOrderAmendmentQuoteOrFail())
            ->findOne();

        $salesOrderAmendmentQuoteEntity = $this->getFactory()
            ->createSalesOrderAmendmentQuoteMapper()
            ->mapSalesOrderAmendmentQuoteTransferToSalesOrderAmendmentQuoteEntity(
                $salesOrderAmendmentQuoteTransfer,
                $salesOrderAmendmentQuoteEntity,
                $quoteFieldsAllowedForSaving,
            );

        $salesOrderAmendmentQuoteEntity->save();

        return $this->getFactory()
            ->createSalesOrderAmendmentQuoteMapper()
            ->mapSalesOrderAmendmentQuoteEntityToSalesOrderAmendmentQuoteTransfer(
                $salesOrderAmendmentQuoteEntity,
                $salesOrderAmendmentQuoteTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    public function updateSalesOrderAmendment(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): SalesOrderAmendmentTransfer
    {
        /** @var \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendment $salesOrderAmendmentEntity */
        $salesOrderAmendmentEntity = $this->getFactory()
            ->getSalesOrderAmendmentQuery()
            ->filterByUuid($salesOrderAmendmentTransfer->getUuidOrFail())
            ->findOne();

        $salesOrderAmendmentMapper = $this->getFactory()->createSalesOrderAmendmentMapper();
        $salesOrderAmendmentEntity = $salesOrderAmendmentMapper->mapSalesOrderAmendmentTransferToSalesOrderAmendmentEntity(
            $salesOrderAmendmentTransfer,
            $salesOrderAmendmentEntity,
        );

        $salesOrderAmendmentEntity->save();

        return $salesOrderAmendmentMapper->mapSalesOrderAmendmentEntityToSalesOrderAmendmentTransfer(
            $salesOrderAmendmentEntity,
            $salesOrderAmendmentTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return void
     */
    public function deleteSalesOrderAmendment(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $salesOrderAmendmentEntities */
        $salesOrderAmendmentEntities = $this->getFactory()
            ->getSalesOrderAmendmentQuery()
            ->filterByUuid($salesOrderAmendmentTransfer->getUuidOrFail())
            ->find();

        $salesOrderAmendmentEntities->delete();
    }

    /**
     * @param list<int> $salesOrderAmendmentQuoteIds
     *
     * @return void
     */
    public function deleteSalesOrderAmendmentQuotes(
        array $salesOrderAmendmentQuoteIds
    ): void {
        $this->getFactory()
            ->getSalesOrderAmendmentQuoteQuery()
            ->filterByIdSalesOrderAmendmentQuote_In($salesOrderAmendmentQuoteIds)
            ->delete();
    }
}
