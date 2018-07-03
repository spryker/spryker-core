<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event;

use Orm\Zed\ProductGroup\Persistence\Map\SpyProductAbstractGroupTableMap;
use Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery;
use Spryker\Shared\ProductGroupStorage\ProductGroupStorageConstants;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductGroup\Dependency\ProductGroupEvents;

/**
 * @method \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductGroupStorage\Communication\ProductGroupStorageCommunicationFactory getFactory()
 */
class ProductGroupEventResourcePlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
{
    /**
     * Specification:
     *  - Returns the name of resource
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductGroupStorageConstants::PRODUCT_GROUP_RESOURCE_NAME;
    }

    /**
     * Specification:
     *  - Returns query of resource entity, provided $ids parameter
     *    will apply to query to limit the result
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryData($ids = []): SpyProductAbstractGroupQuery
    {
        $query = $this->getQueryContainer()->queryProductAbstractGroupByGroupIds($ids);

        if (empty($ids)) {
            $query->clear();
        }

        return $query;
    }

    /**
     * Specification:
     *  - Returns the event name of resource entity
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return ProductGroupEvents::PRODUCT_GROUP_PUBLISH;
    }

    /**
     * Specification:
     *  - Returns the name of ID column for publishing
     *
     * @api
     *
     * @return string
     */
    public function getIdColumnName(): string
    {
        return SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_ABSTRACT;
    }
}
