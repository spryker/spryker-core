<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UrlExporter\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerFeature\Zed\Url\Persistence\Propel\Map\SpyRedirectTableMap;
use SprykerFeature\Zed\Url\Persistence\Propel\Map\SpyUrlTableMap;
use SprykerFeature\Zed\Url\Persistence\Propel\ResourceAwareSpyUrlTableMap;

class UrlExporterQueryContainer extends AbstractQueryContainer implements UrlExporterQueryContainerInterface
{

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandRedirectQuery(ModelCriteria $expandableQuery)
    {
        $expandableQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyRedirectTableMap::COL_ID_REDIRECT,
            Criteria::INNER_JOIN
        );

        $expandableQuery->addJoin(
            SpyRedirectTableMap::COL_ID_REDIRECT,
            SpyUrlTableMap::COL_FK_RESOURCE_REDIRECT_ID,
            Criteria::INNER_JOIN
        );

        $expandableQuery->clearSelectColumns();
        $expandableQuery->withColumn(SpyRedirectTableMap::COL_ID_REDIRECT, 'redirect_id');
        $expandableQuery->withColumn(SpyUrlTableMap::COL_URL, 'from_url');
        $expandableQuery->withColumn(SpyRedirectTableMap::COL_STATUS, 'status');
        $expandableQuery->withColumn(SpyRedirectTableMap::COL_TO_URL, 'to_url');

        return $expandableQuery;
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandUrlQuery(ModelCriteria $expandableQuery)
    {
        $expandableQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyUrlTableMap::COL_ID_URL,
            Criteria::INNER_JOIN
        );

        foreach (ResourceAwareSpyUrlTableMap::getResourceColumnNames() as $constantName => $value) {
            $alias = strstr($value, 'fk_resource');
            $expandableQuery->withColumn(ResourceAwareSpyUrlTableMap::getConstantValue($constantName), $alias);
        }

        $expandableQuery->withColumn(SpyUrlTableMap::COL_URL, 'url');

        return $expandableQuery;
    }

}
