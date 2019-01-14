<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\QuoteRequest\Persistence\Map\SpyQuoteRequestTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestPersistenceFactory getFactory()
 */
class QuoteRequestRepository extends AbstractRepository implements QuoteRequestRepositoryInterface
{
    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByIdCompanyUser(int $idCompanyUser): QuoteRequestCollectionTransfer
    {
        $quoteRequestsQuery = $this->createCustomerQuoteRequestQuery($idCompanyUser);
        $quoteRequestsEntityTransferCollection = $this->buildQueryFromCriteria($quoteRequestsQuery)->find();

        return $this->getFactory()
            ->createQuoteRequestMapper()
            ->mapEntityCollectionToTransferCollection($quoteRequestsEntityTransferCollection);
    }

    /**
     * @module Customer
     *
     * @param int $idCompanyUser
     *
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery
     */
    protected function createCustomerQuoteRequestQuery(int $idCompanyUser)
    {
        return $this->getFactory()
            ->createQuoteRequestQuery()
            ->addJoin(SpyQuoteRequestTableMap::COL_FK_COMPANY_USER, SpyCompanyUserTableMap::COL_ID_COMPANY_USER, Criteria::LEFT_JOIN)
            ->filterByFkCompanyUser($idCompanyUser)
            ->orderByIdQuoteRequest()
            ->leftJoinWithSpyQuoteRequestVersion();
    }
}
