<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Communication\CmsBlockCategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 */
class CmsBlockCategoryConnectorEntityStoragePublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $categoryIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY);

        $this->getFacade()->publish($categoryIds);
    }
}
