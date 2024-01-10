<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\TaxAppConfigCollectionTransfer;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
use Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\TaxApp\Persistence\TaxAppPersistenceFactory getFactory()
 */
class TaxAppRepository extends AbstractRepository implements TaxAppRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppConfigCollectionTransfer
     */
    public function getTaxAppConfigCollection(TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer): TaxAppConfigCollectionTransfer
    {
        $taxAppCollectionTransfer = new TaxAppConfigCollectionTransfer();
        $taxAppConfigQuery = $this->getFactory()->createTaxAppConfigQuery();
        $taxAppConfigQuery = $this->applyTaxAppConfigFilters($taxAppConfigQuery, $taxAppConfigCriteriaTransfer);

        $paginationTransfer = $taxAppConfigCriteriaTransfer->getPagination();

        if ($paginationTransfer) {
            $taxAppConfigQuery = $this->applyTaxAppConfigPagination($taxAppConfigQuery, $paginationTransfer);
        }

        $hasSortCollection = count($taxAppConfigCriteriaTransfer->getSortCollection());
        if ($hasSortCollection) {
            $taxAppConfigQuery = $this->applyTaxAppConfigSorting($taxAppConfigQuery, $taxAppConfigCriteriaTransfer);
        }

        $taxAppConfigEntities = $taxAppConfigQuery->find();

        return $this->getFactory()->createTaxAppConfigMapper()
            ->mapTaxAppConfigEntitiesToTaxAppConfigCollectionTransfer($taxAppConfigEntities, $taxAppCollectionTransfer);
    }

    /**
     * @param \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery $taxAppConfigQuery
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery
     */
    protected function applyTaxAppConfigFilters(
        SpyTaxAppConfigQuery $taxAppConfigQuery,
        TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
    ): SpyTaxAppConfigQuery {
        if (!$taxAppConfigCriteriaTransfer->getTaxAppConfigConditions()) {
            return $taxAppConfigQuery;
        }

        if ($taxAppConfigCriteriaTransfer->getTaxAppConfigConditions()->getFkStores()) {
            $taxAppConfigQuery->filterByFkStore_In($taxAppConfigCriteriaTransfer->getTaxAppConfigConditions()->getFkStores());
        }

        if ($taxAppConfigCriteriaTransfer->getTaxAppConfigConditions()->getVendorCodes()) {
            $taxAppConfigQuery->filterByVendorCode_In($taxAppConfigCriteriaTransfer->getTaxAppConfigConditions()->getVendorCodes());
        }

        if ($taxAppConfigCriteriaTransfer->getTaxAppConfigConditions()->getApplicationIds()) {
            $taxAppConfigQuery->filterByIdTaxAppConfig_In($taxAppConfigCriteriaTransfer->getTaxAppConfigConditions()->getApplicationIds());
        }

        return $taxAppConfigQuery;
    }

    /**
     * @param \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery $taxAppConfigQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery
     */
    protected function applyTaxAppConfigPagination(
        SpyTaxAppConfigQuery $taxAppConfigQuery,
        PaginationTransfer $paginationTransfer
    ): SpyTaxAppConfigQuery {
        $paginationTransfer->setNbResults($taxAppConfigQuery->count());

        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $taxAppConfigQuery
                ->limit($paginationTransfer->getLimit())
                ->offset($paginationTransfer->getOffset());
        }

        return $taxAppConfigQuery;
    }

    /**
     * @param \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery $taxAppConfigQuery
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery
     */
    protected function applyTaxAppConfigSorting(
        SpyTaxAppConfigQuery $taxAppConfigQuery,
        TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
    ): SpyTaxAppConfigQuery {
        $sortCollection = $taxAppConfigCriteriaTransfer->getSortCollection();
        foreach ($sortCollection as $sortTransfer) {
            $taxAppConfigQuery->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $taxAppConfigQuery;
    }
}
