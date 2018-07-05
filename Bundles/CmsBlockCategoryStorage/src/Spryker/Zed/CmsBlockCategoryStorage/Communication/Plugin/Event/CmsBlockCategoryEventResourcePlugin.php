<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event;

use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery;
use Spryker\Shared\CmsBlockCategoryStorage\CmsBlockCategoryStorageConstants;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\CmsBlockCategoryConnectorEvents;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Communication\CmsBlockCategoryStorageCommunicationFactory getFactory()
 */
class CmsBlockCategoryEventResourcePlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
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
        return CmsBlockCategoryStorageConstants::CMS_BLOCK_CATEGORY_RESOURCE_NAME;
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
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryData(array $ids = []): SpyCmsBlockCategoryConnectorQuery
    {
        $query = $this->getQueryContainer()->queryCmsBlockCategoriesByIds($ids);

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
        return CmsBlockCategoryConnectorEvents::CMS_BLOCK_CATEGORY_CONNECTOR_PUBLISH;
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
        return SpyCmsBlockCategoryConnectorTableMap::COL_ID_CMS_BLOCK_CATEGORY_CONNECTOR;
    }
}
