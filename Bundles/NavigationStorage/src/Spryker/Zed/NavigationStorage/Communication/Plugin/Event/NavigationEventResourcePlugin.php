<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Communication\Plugin\Event;

use Orm\Zed\Navigation\Persistence\Map\SpyNavigationTableMap;
use Spryker\Shared\NavigationStorage\NavigationStorageConstants;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourcePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Navigation\Dependency\NavigationEvents;

/**
 * @method \Spryker\Zed\NavigationStorage\Persistence\NavigationStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\NavigationStorage\Business\NavigationStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\NavigationStorage\Communication\NavigationStorageCommunicationFactory getFactory()
 */
class NavigationEventResourcePlugin extends AbstractPlugin implements EventResourcePluginInterface
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
        return NavigationStorageConstants::RESOURCE_NAME;
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
        return $this->getQueryContainer()->queryNavigation();
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
        return NavigationEvents::NAVIGATION_KEY_PUBLISH;
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
        return SpyNavigationTableMap::COL_ID_NAVIGATION;
    }
}
