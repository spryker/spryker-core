<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event;

use Orm\Zed\CmsBlockProductConnector\Persistence\Map\SpyCmsBlockProductConnectorTableMap;
use Spryker\Shared\CmsBlockProductStorage\CmsBlockProductStorageConstants;
use Spryker\Zed\CmsBlockProductConnector\Dependency\CmsBlockProductConnectorEvents;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockProductStorage\Communication\CmsBlockProductStorageCommunicationFactory getFactory()
 */
class CmsBlockProductEventResourcePlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
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
        return CmsBlockProductStorageConstants::CMS_BLOCK_PRODUCT_RESOURCE_NAME;
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
        return $this->getQueryContainer()->queryAllCmsBlockProducts();
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
        return CmsBlockProductConnectorEvents::CMS_BLOCK_PRODUCT_CONNECTOR_PUBLISH;
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
        return SpyCmsBlockProductConnectorTableMap::COL_ID_CMS_BLOCK_PRODUCT_CONNECTOR;
    }
}
