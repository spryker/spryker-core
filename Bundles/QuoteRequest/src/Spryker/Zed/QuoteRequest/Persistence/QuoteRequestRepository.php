<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use DateTime;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Orm\Zed\QuoteRequest\Persistence\Map\SpyQuoteRequestTableMap;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestPersistenceFactory getFactory()
 */
class QuoteRequestRepository extends AbstractRepository implements QuoteRequestRepositoryInterface
{
    /**
     * @module Customer
     * @module CompanyUser
     * @module Company
     * @module CompanyBusinessUnit
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(
        QuoteRequestFilterTransfer $quoteRequestFilterTransfer
    ): QuoteRequestCollectionTransfer {
        $quoteRequestQuery = $this->getFactory()
            ->getQuoteRequestPropelQuery()
            ->joinWithCompanyUser()
            ->useCompanyUserQuery()
                ->joinWithCustomer()
                ->joinWithCompany()
                ->joinWithCompanyBusinessUnit()
            ->endUse()
            ->orderByIdQuoteRequest(Criteria::DESC);

        $quoteRequestQuery = $this->setQuoteRequestFilters($quoteRequestQuery, $quoteRequestFilterTransfer);

        return $this->getFactory()
            ->createQuoteRequestMapper()
            ->mapEntityCollectionToTransferCollection($quoteRequestQuery->find())
            ->setPagination($quoteRequestFilterTransfer->getPagination());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer
     */
    public function getQuoteRequestVersionCollectionByFilter(QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer): QuoteRequestVersionCollectionTransfer
    {
        $quoteRequestVersionQuery = $this->getFactory()
            ->getQuoteRequestVersionPropelQuery()
            ->joinWithSpyQuoteRequest()
            ->orderByIdQuoteRequestVersion(Criteria::DESC);

        if ($quoteRequestVersionFilterTransfer->getQuoteRequest() && $quoteRequestVersionFilterTransfer->getQuoteRequest()->getIdQuoteRequest()) {
            $quoteRequestVersionQuery->filterByFkQuoteRequest($quoteRequestVersionFilterTransfer->getQuoteRequest()->getIdQuoteRequest());
        }

        if ($quoteRequestVersionFilterTransfer->getQuoteRequestVersionReference()) {
            $quoteRequestVersionQuery->filterByVersionReference($quoteRequestVersionFilterTransfer->getQuoteRequestVersionReference());
        }

        return $this->getFactory()
            ->createQuoteRequestVersionMapper()
            ->mapEntityCollectionToTransferCollection($quoteRequestVersionQuery->find());
    }

    /**
     * @return int[]
     */
    public function getOutdatedQuoteRequestIds(): array
    {
        return $this->getFactory()
            ->getQuoteRequestPropelQuery()
            ->filterByStatus(SharedQuoteRequestConfig::STATUS_READY)
            ->filterByValidUntil((new DateTime())->format('Y-m-d H:i:s'), Criteria::LESS_EQUAL)
            ->select([SpyQuoteRequestTableMap::COL_ID_QUOTE_REQUEST])
            ->find()
            ->toArray();
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery $quoteRequestQuery
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery
     */
    protected function setQuoteRequestFilters(
        SpyQuoteRequestQuery $quoteRequestQuery,
        QuoteRequestFilterTransfer $quoteRequestFilterTransfer
    ): SpyQuoteRequestQuery {
        if (!$quoteRequestFilterTransfer->getWithHidden()) {
            $quoteRequestQuery->filterByIsHidden(false);
        }

        if ($quoteRequestFilterTransfer->getExcludedStatuses()) {
            $quoteRequestQuery->filterByStatus($quoteRequestFilterTransfer->getExcludedStatuses(), Criteria::NOT_IN);
        }

        if ($quoteRequestFilterTransfer->getCompanyUser() && $quoteRequestFilterTransfer->getCompanyUser()->getIdCompanyUser()) {
            $quoteRequestQuery->filterByFkCompanyUser($quoteRequestFilterTransfer->getCompanyUser()->getIdCompanyUser());
        }

        if ($quoteRequestFilterTransfer->getQuoteRequestReference()) {
            $quoteRequestQuery->filterByQuoteRequestReference($quoteRequestFilterTransfer->getQuoteRequestReference());
        }

        if ($quoteRequestFilterTransfer->getPagination()) {
            $quoteRequestQuery = $this->preparePagination($quoteRequestQuery, $quoteRequestFilterTransfer->getPagination());
        }

        return $quoteRequestQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery
     */
    protected function preparePagination(ModelCriteria $query, PaginationTransfer $paginationTransfer): SpyQuoteRequestQuery
    {
        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        $paginationModel = $query->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults())
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getQuery();
    }
}
