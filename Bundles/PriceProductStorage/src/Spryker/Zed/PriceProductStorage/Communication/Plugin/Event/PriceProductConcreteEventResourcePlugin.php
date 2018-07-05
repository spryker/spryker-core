<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Communication\Plugin\Event;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;

/**
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductStorage\Communication\PriceProductStorageCommunicationFactory getFactory()
 */
class PriceProductConcreteEventResourcePlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
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
        return PriceProductStorageConstants::PRICE_CONCRETE_RESOURCE_NAME;
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
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryData(array $ids = []): SpyPriceProductQuery
    {
        $query = $this->getQueryContainer()->queryAllProductIdsByPriceProductIds($ids);

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
        return PriceProductEvents::PRICE_CONCRETE_PUBLISH;
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
        return SpyPriceProductTableMap::COL_FK_PRODUCT;
    }
}
