<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Persistence;

use SprykerFeature\Zed\FrontendExporter\Persistence\Propel\SpyFrontendExporterTouchQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;

class FrontendExporterQueryContainer extends AbstractQueryContainer
{

    /**
     * @param string $type
     * @param \DateTime $lastExportedAt
     *
     * @return SpyFrontendExporterTouchQuery
     */
    public function createBasicExportableQuery($type, \DateTime $lastExportedAt)
    {
        return SpyTouchQuery::create()
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->filterByTouched(['min' => $lastExportedAt])
            ->filterByItemType($type);
    }

    /**
     * @throws PropelException
     *
     * @return SpyTouchQuery
     */
    public function queryExportTypes()
    {
        $query = SpyTouchQuery::create()
            ->addSelectColumn(SpyTouchTableMap::COL_ITEM_TYPE)
            ->setDistinct()
            ->setFormatter(new PropelArraySetFormatter())
        ;

        return $query;
    }

}
