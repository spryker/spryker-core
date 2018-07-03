<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Spryker\Shared\CmsBlockStorage\CmsBlockStorageConstants;
use Spryker\Zed\CmsBlock\Dependency\CmsBlockEvents;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockStorage\Communication\CmsBlockStorageCommunicationFactory getFactory()
 */
class CmsBlockEventResourcePlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
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
        return CmsBlockStorageConstants::CMS_BLOCK_RESOURCE_NAME;
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
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryData($ids = []): SpyCmsBlockQuery
    {
        $query = $this->getQueryContainer()->queryCmsBlockByIds($ids);

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
        return CmsBlockEvents::CMS_BLOCK_PUBLISH;
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
        return SpyCmsBlockTableMap::COL_ID_CMS_BLOCK;
    }
}
