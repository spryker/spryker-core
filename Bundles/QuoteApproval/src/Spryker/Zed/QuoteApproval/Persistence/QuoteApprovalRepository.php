<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalPersistenceFactory getFactory()
 */
class QuoteApprovalRepository extends AbstractRepository implements QuoteApprovalRepositoryInterface
{
    /**
     * @module CompanyUser
     * @module Customer
     *
     * @param int[] $quoteIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    protected function getQuoteApprovalsEntitiesByQuoteIds(array $quoteIds): ObjectCollection
    {
        return $this->getFactory()
            ->createQuoteApprovalPropelQuery()
            ->joinWithSpyCompanyUser()
            ->useSpyCompanyUserQuery()
                ->joinWithCustomer()
            ->endUse()
            ->filterByFkQuote_In($quoteIds)
            ->orderByIdQuoteApproval()
            ->find();
    }

    /**
     * @module CompanyUser
     * @module Customer
     *
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer[]
     */
    public function getQuoteApprovalsByIdQuote(int $idQuote): array
    {
        $quoteApprovalEntities = $this->getQuoteApprovalsEntitiesByQuoteIds([$idQuote]);

        $quoteApprovalTransfers = [];

        $mapper = $this->getFactory()
            ->createQuoteApprovalMapper();

        foreach ($quoteApprovalEntities as $quoteApprovalEntity) {
            $quoteApprovalTransfers[] = $mapper
                ->mapQuoteApprovalEntityToTransfer(
                    $quoteApprovalEntity,
                    new QuoteApprovalTransfer()
                );
        }

        return $quoteApprovalTransfers;
    }

    /**
     * @module CompanyUser
     * @module Customer
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer[]
     */
    public function getQuoteApprovals(QuoteApprovalRequestTransfer $quoteApprovalsRequestTransfer): array
    {
        $quoteIds = $quoteApprovalsRequestTransfer->getQuoteIds();
        $quoteApprovalEntities = $this->getQuoteApprovalsEntitiesByQuoteIds($quoteIds);

        $quoteApprovalTransfers = [];

        $mapper = $this->getFactory()
            ->createQuoteApprovalMapper();
        foreach ($quoteApprovalEntities as $quoteApprovalEntity) {
            $quoteId = $quoteApprovalEntity->getFkQuote();
            if ($quoteApprovalTransfers[$quoteId] === null) {
                $quoteApprovalTransfers[$quoteId] = [];
            }

            $quoteApprovalTransfers[$quoteId][] = $mapper
                ->mapQuoteApprovalEntityToTransfer(
                    $quoteApprovalEntity,
                    new QuoteApprovalTransfer()
                );
        }

        return $quoteApprovalTransfers;
    }

    /**
     * @param int $idQuoteApproval
     *
     * @return int|null
     */
    public function findIdQuoteByIdQuoteApproval(int $idQuoteApproval): ?int
    {
        $quoteApprovalEntity = $this->getFactory()
            ->createQuoteApprovalPropelQuery()
            ->filterByIdQuoteApproval($idQuoteApproval)
            ->findOne();

        return $quoteApprovalEntity ? $quoteApprovalEntity->getFkQuote() : null;
    }

    /**
     * @module CompanyUser
     * @module Customer
     *
     * @param int $idQuoteApproval
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer|null
     */
    public function findQuoteApprovalById(int $idQuoteApproval): ?QuoteApprovalTransfer
    {
        $quoteApprovalEntity = $this->getFactory()
            ->createQuoteApprovalPropelQuery()
            ->joinWithSpyCompanyUser()
                ->useSpyCompanyUserQuery()
            ->joinWithCustomer()
            ->endUse()
            ->filterByIdQuoteApproval($idQuoteApproval)
            ->find()
            ->getFirst();

        if ($quoteApprovalEntity === null) {
            return null;
        }

        $quoteApprovalTransfer = $this->getFactory()
            ->createQuoteApprovalMapper()
            ->mapQuoteApprovalEntityToTransfer($quoteApprovalEntity, new QuoteApprovalTransfer());

        return $quoteApprovalTransfer;
    }
}
