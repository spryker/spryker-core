<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\CommentCollectionTransfer;
use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentVersionCollectionTransfer;
use Generated\Shared\Transfer\CommentVersionFilterTransfer;
use Orm\Zed\Comment\Persistence\Map\SpyCommentTableMap;
use Orm\Zed\Comment\Persistence\Map\SpyCommentVersionTableMap;
use Orm\Zed\Comment\Persistence\SpyCommentQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Comment\Persistence\CommentPersistenceFactory getFactory()
 */
class CommentRepository extends AbstractRepository implements CommentRepositoryInterface
{
    /**
     * @module Customer
     * @module CompanyUser
     * @module Company
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentCollectionTransfer
     */
    public function getCommentCollectionByFilter(
        CommentFilterTransfer $quoteRequestFilterTransfer
    ): CommentCollectionTransfer {
        $quoteRequestQuery = $this->getFactory()
            ->getCommentPropelQuery()
            ->joinWithCompanyUser()
            ->useCompanyUserQuery()
                ->joinWithCustomer()
                ->joinWithCompany()
            ->endUse()
            ->orderByIdComment(Criteria::DESC);

        $quoteRequestQuery = $this->setCommentFilters($quoteRequestQuery, $quoteRequestFilterTransfer);

        return $this->getFactory()
            ->createCommentMapper()
            ->mapEntityCollectionToTransferCollection($quoteRequestQuery->find())
            ->setPagination($quoteRequestFilterTransfer->getPagination());
    }

    /**
     * @param \Generated\Shared\Transfer\CommentVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentVersionCollectionTransfer
     */
    public function getCommentVersionCollectionByFilter(CommentVersionFilterTransfer $quoteRequestVersionFilterTransfer): CommentVersionCollectionTransfer
    {
        $quoteRequestVersionQuery = $this->getFactory()
            ->getCommentVersionPropelQuery()
            ->joinWithSpyComment()
            ->orderByVersion(Criteria::DESC);

        if ($quoteRequestVersionFilterTransfer->getComment() && $quoteRequestVersionFilterTransfer->getComment()->getIdComment()) {
            $quoteRequestVersionQuery->filterByFkComment($quoteRequestVersionFilterTransfer->getComment()->getIdComment());
        }

        if ($quoteRequestVersionFilterTransfer->getCommentVersionReference()) {
            $quoteRequestVersionQuery->filterByVersionReference($quoteRequestVersionFilterTransfer->getCommentVersionReference());
        }

        return $this->getFactory()
            ->createCommentVersionMapper()
            ->mapEntityCollectionToTransferCollection($quoteRequestVersionQuery->find());
    }

    /**
     * @module CompanyUser
     * @module Customer
     *
     * @param string $customerReference
     *
     * @return int
     */
    public function countCustomerComments(string $customerReference): int
    {
        return $this->getFactory()
            ->getCommentPropelQuery()
            ->useCompanyUserQuery()
                ->useCustomerQuery()
                    ->filterByCustomerReference($customerReference)
                ->endUse()
            ->endUse()
            ->count();
    }

    /**
     * @module Customer
     * @module CompanyUser
     * @module Company
     *
     * @param string $versionReference
     *
     * @return \Generated\Shared\Transfer\CommentTransfer|null
     */
    public function findCommentByVersionReference(string $versionReference): ?CommentTransfer
    {
        $quoteRequestEntity = $this->getFactory()
            ->getCommentPropelQuery()
            ->joinWithCompanyUser()
            ->useCompanyUserQuery()
                ->joinWithCustomer()
                ->joinWithCompany()
            ->endUse()
            ->useSpyCommentVersionQuery()
                ->filterByVersionReference($versionReference)
            ->endUse()
            ->findOne();

        if (!$quoteRequestEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCommentMapper()
            ->mapCommentEntityToCommentTransfer($quoteRequestEntity, new CommentTransfer());
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyCommentQuery $quoteRequestQuery
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Orm\Zed\Comment\Persistence\SpyCommentQuery
     */
    protected function setCommentFilters(
        SpyCommentQuery $quoteRequestQuery,
        CommentFilterTransfer $quoteRequestFilterTransfer
    ): SpyCommentQuery {
        if ($quoteRequestFilterTransfer->getExcludedStatuses()) {
            $quoteRequestQuery->filterByStatus($quoteRequestFilterTransfer->getExcludedStatuses(), Criteria::NOT_IN);
        }

        if ($quoteRequestFilterTransfer->getCompanyUser() && $quoteRequestFilterTransfer->getCompanyUser()->getIdCompanyUser()) {
            $quoteRequestQuery->filterByFkCompanyUser($quoteRequestFilterTransfer->getCompanyUser()->getIdCompanyUser());
        }

        if ($quoteRequestFilterTransfer->getCommentReference()) {
            $quoteRequestQuery->filterByCommentReference($quoteRequestFilterTransfer->getCommentReference());
        }

        if ($quoteRequestFilterTransfer->getIdComment()) {
            $quoteRequestQuery->filterByIdComment($quoteRequestFilterTransfer->getIdComment());
        }

        if (!$quoteRequestFilterTransfer->getWithHidden()) {
            $quoteRequestQuery = $this->addExcludeHiddenCommentFilter($quoteRequestQuery);
        }

        if ($quoteRequestFilterTransfer->getPagination()) {
            $quoteRequestQuery = $this->preparePagination($quoteRequestQuery, $quoteRequestFilterTransfer->getPagination());
        }

        return $quoteRequestQuery;
    }

    /**
     * Provided query can not contain group by, offset, or limit directives otherwise it will alter the results.
     *
     * @param \Orm\Zed\Comment\Persistence\SpyCommentQuery $quoteRequestQuery
     *
     * @return \Orm\Zed\Comment\Persistence\SpyCommentQuery
     */
    protected function addExcludeHiddenCommentFilter(SpyCommentQuery $quoteRequestQuery): SpyCommentQuery
    {
        $hiddenCommentQuery = clone $quoteRequestQuery;

        $hiddenCommentIds = $hiddenCommentQuery
            ->joinSpyCommentVersion()
            ->filterByIsLatestVersionVisible(false)
            ->groupByIdComment()
            ->having(sprintf(
                'COUNT(%s) = 1',
                SpyCommentVersionTableMap::COL_FK_QUOTE_REQUEST
            ))
            ->select([
                SpyCommentTableMap::COL_ID_QUOTE_REQUEST,
            ])
            ->find()
            ->toArray();

        $quoteRequestQuery->filterByIdComment($hiddenCommentIds, Criteria::NOT_IN);

        return $quoteRequestQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\Comment\Persistence\SpyCommentQuery
     */
    protected function preparePagination(ModelCriteria $query, PaginationTransfer $paginationTransfer): SpyCommentQuery
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
