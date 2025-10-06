<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence;

use Generated\Shared\Transfer\UrlCollectionTransfer;
use Generated\Shared\Transfer\UrlCriteriaTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Url\Persistence\UrlPersistenceFactory getFactory()
 */
class UrlRepository extends AbstractRepository implements UrlRepositoryInterface
{
    public function findUrlCaseInsensitive(UrlTransfer $urlTransfer): ?UrlTransfer
    {
        $urlEntity = $this->prepareUrlCaseInsensitiveQuery($urlTransfer)->findOne();

        if ($urlEntity === null) {
            return null;
        }

        return (new UrlTransfer())->fromArray($urlEntity->toArray());
    }

    public function hasUrlCaseInsensitive(UrlTransfer $urlTransfer, bool $ignoreRedirects): bool
    {
        $urlQuery = $this->prepareUrlCaseInsensitiveQuery($urlTransfer);

        if ($ignoreRedirects) {
            $urlQuery->filterByFkResourceRedirect(null, Criteria::ISNULL);
        }

        return $urlQuery->exists();
    }

    public function getUrlCollection(UrlCriteriaTransfer $urlCriteriaTransfer): UrlCollectionTransfer
    {
        $urlQuery = $this->getFactory()->createUrlQuery()->setIgnoreCase(true);

        $urlQuery = $this->applyUrlCriteria($urlCriteriaTransfer, $urlQuery);
        $urlQuery = $this->applyUrlSortings($urlCriteriaTransfer, $urlQuery);
        $urlQuery = $this->applyUrlPagination($urlCriteriaTransfer, $urlQuery);

        $urlEntities = $urlQuery->find();

        $urlCollectionTransfer = (new UrlCollectionTransfer())
            ->setPagination($urlCriteriaTransfer->getPagination());

        return $this->getFactory()
            ->createUrlMapper()
            ->mapUrlEntitiesToUrlCollectionTransfer($urlEntities, $urlCollectionTransfer);
    }

    public function applyUrlPagination(UrlCriteriaTransfer $urlCriteriaTransfer, SpyUrlQuery $urlQuery): SpyUrlQuery
    {
        if ($urlCriteriaTransfer->getPagination()) {
            $urlCriteriaTransfer->getPagination()->setNbResults($urlQuery->count());
            $urlQuery->setLimit($urlCriteriaTransfer->getPagination()->getLimit())
                ->setOffset($urlCriteriaTransfer->getPagination()->getOffset());
        }

        return $urlQuery;
    }

    public function applyUrlSortings(UrlCriteriaTransfer $urlCriteriaTransfer, SpyUrlQuery $urlQuery): SpyUrlQuery
    {
        foreach ($urlCriteriaTransfer->getSortCollection() as $sortTransfer) {
            $urlQuery->orderBy($sortTransfer->getField(), $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC);
        }

        return $urlQuery;
    }

    public function applyUrlCriteria(UrlCriteriaTransfer $urlCriteriaTransfer, SpyUrlQuery $urlQuery): SpyUrlQuery
    {
        if (!$urlCriteriaTransfer->getUrlConditions()) {
            return $urlQuery;
        }

        if ($urlCriteriaTransfer->getUrlConditions()->getLocaleIds()) {
            $urlQuery->filterByFkLocale_In($urlCriteriaTransfer->getUrlConditions()->getLocaleIds());
        }

        if ($urlCriteriaTransfer->getUrlConditions()->getResourceProductAbstractIds()) {
            $urlQuery->filterByFkResourceProductAbstract_In($urlCriteriaTransfer->getUrlConditions()->getResourceProductAbstractIds());
        }

        if ($urlCriteriaTransfer->getUrlConditions()->getNotResourceProductAbstractIds()) {
            $urlQuery->filterBy(
                SpyUrlTableMap::translateFieldName(SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT, TableMap::TYPE_COLNAME, TableMap::TYPE_PHPNAME),
                $urlCriteriaTransfer->getUrlConditions()->getNotResourceProductAbstractIds(),
                Criteria::NOT_IN,
            );
        }

        if ($urlCriteriaTransfer->getUrlConditions()->getUrls()) {
            $urlQuery->filterByUrl_In($urlCriteriaTransfer->getUrlConditions()->getUrls());
        }

        return $urlQuery;
    }

    protected function prepareUrlCaseInsensitiveQuery(UrlTransfer $urlTransfer): SpyUrlQuery
    {
        $urlQuery = $this->getFactory()
            ->createUrlQuery()
            ->setIgnoreCase($this->isIgnoreCaseApplicable());

        if ($urlTransfer->getUrl() !== null) {
            return $urlQuery->filterByUrl($urlTransfer->getUrl());
        }

        return $urlQuery->filterByIdUrl($urlTransfer->getIdUrl());
    }

    protected function isIgnoreCaseApplicable(): bool
    {
        return $this->getFactory()
            ->getPropelFacade()
            ->isCollationCaseSensitive();
    }
}
