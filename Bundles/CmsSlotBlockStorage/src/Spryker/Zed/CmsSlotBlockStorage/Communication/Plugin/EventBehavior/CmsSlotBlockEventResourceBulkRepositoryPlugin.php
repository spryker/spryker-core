<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Communication\Plugin\EventBehavior;

use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\CmsSlotBlock\Persistence\Map\SpyCmsSlotBlockTableMap;
use Spryker\Shared\CmsSlotBlockStorage\CmsSlotBlockStorageConfig;
use Spryker\Zed\CmsSlotBlock\Dependency\CmsSlotBlockEvents;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceBulkRepositoryPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CmsSlotBlockStorage\CmsSlotBlockStorageConfig getConfig()
 * @method \Spryker\Zed\CmsSlotBlockStorage\Business\CmsSlotBlockStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsSlotBlockStorage\Communication\CmsSlotBlockStorageCommunicationFactory getFactory()
 */
class CmsSlotBlockEventResourceBulkRepositoryPlugin extends AbstractPlugin implements EventResourceBulkRepositoryPluginInterface
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
        return CmsSlotBlockStorageConfig::CMS_SLOT_BLOCK_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function getData(int $offset, int $limit): array
    {
        return $this->getFacade()
            ->getCmsSlotBlockCollection($this->createCmsSlotBlockCriteriaTransfer($offset, $limit))
            ->getCmsSlotBlocks()
            ->getArrayCopy();
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
        return CmsSlotBlockEvents::CMS_SLOT_BLOCK_PUBLISH;
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
        return SpyCmsSlotBlockTableMap::COL_ID_CMS_SLOT_BLOCK;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer
     */
    protected function createCmsSlotBlockCriteriaTransfer(int $offset, int $limit): CmsSlotBlockCriteriaTransfer
    {
        $filterTransfer = (new FilterTransfer())
            ->setOrderBy($this->getIdColumnName())
            ->setOffset($offset)
            ->setLimit($limit);

        return (new CmsSlotBlockCriteriaTransfer())
            ->setFilter($filterTransfer);
    }
}
