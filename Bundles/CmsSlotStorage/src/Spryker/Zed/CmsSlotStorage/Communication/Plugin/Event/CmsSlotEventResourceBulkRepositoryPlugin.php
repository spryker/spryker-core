<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Communication\Plugin\Event;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTableMap;
use Spryker\Shared\CmsSlotStorage\CmsSlotStorageConstants;
use Spryker\Zed\CmsSlot\Dependency\CmsSlotEvents;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceBulkRepositoryPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsSlotStorage\CmsSlotStorageConfig getConfig()
 * @method \Spryker\Zed\CmsSlotStorage\Communication\CmsSlotStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotStorage\Business\CmsSlotStorageFacadeInterface getFacade()
 */
class CmsSlotEventResourceBulkRepositoryPlugin extends AbstractPlugin implements EventResourceBulkRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return CmsSlotStorageConstants::CMS_SLOT_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function getData(int $offset, int $limit): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit);

        return $this->getFactory()->getCmsSlotFacade()->getFilteredCmsSlots($filterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return CmsSlotEvents::CMS_SLOT_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return SpyCmsSlotTableMap::COL_ID_CMS_SLOT;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOrderBy(SpyCmsSlotTableMap::COL_ID_CMS_SLOT)
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
