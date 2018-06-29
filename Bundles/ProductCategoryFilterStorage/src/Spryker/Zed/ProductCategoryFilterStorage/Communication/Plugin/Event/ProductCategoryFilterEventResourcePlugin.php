<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Communication\Plugin\Event;

use Orm\Zed\ProductCategoryFilter\Persistence\Map\SpyProductCategoryFilterTableMap;
use Spryker\Shared\ProductCategoryFilterStorage\ProductCategoryFilterStorageConfig;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCategoryFilter\Dependency\ProductCategoryFilterEvents;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Business\ProductCategoryFilterStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Communication\ProductCategoryFilterStorageCommunicationFactory getFactory()
 */
class ProductCategoryFilterEventResourcePlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
{
    /**
     * Specification:
     *  - Returns the name of resource
     *
     * @api
     *
     * @return string
     */
    public function getResourceName()
    {
        return ProductCategoryFilterStorageConfig::PRODUCT_CATEGORY_FILTER_RESOURCE_NAME;
    }

    /**
     * Specification:
     *  - Returns query of resource entity, provided $ids parameter
     *    will apply to query to limit the result
     *
     * @api
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryData()
    {
        return $this->getQueryContainer()->queryProductCategoryFilter();
    }

    /**
     * Specification:
     *  - Returns the event name of resource entity
     *
     * @api
     *
     * @return string
     */
    public function getEventName()
    {
        return ProductCategoryFilterEvents::ENTITY_SPY_PRODUCT_CATEGORY_FILTER_CREATE;
    }

    /**
     * Specification:
     *  - Returns the name of ID column for publishing
     *
     * @api
     *
     * @return string
     */
    public function getIdColumnName()
    {
        return SpyProductCategoryFilterTableMap::COL_ID_PRODUCT_CATEGORY_FILTER;
    }
}
