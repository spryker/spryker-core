<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Communication\Plugin\Event;

use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetTableMap;
use Spryker\Shared\ProductSetStorage\ProductSetStorageConstants;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductSet\Dependency\ProductSetEvents;

/**
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSetStorage\Business\ProductSetStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductSetStorage\Communication\ProductSetStorageCommunicationFactory getFactory()
 */
class ProductSetEventResourcePlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
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
        return ProductSetStorageConstants::PRODUCT_SET_RESOURCE_NAME;
    }

    /**
     * Specification:
     *  - Returns query of resource entity, provided $ids parameter
     *    will apply to query to limit the result
     *
     * @api
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryData()
    {
        return $this->getQueryContainer()->queryProductSet();
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
        return ProductSetEvents::PRODUCT_SET_PUBLISH;
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
        return SpyProductSetTableMap::COL_ID_PRODUCT_SET;
    }
}
