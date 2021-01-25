<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\CmsBlockCategoryConnector\Dependency\CmsBlockCategoryConnectorEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @deprecated Use {@link \Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorStoragePublishListener}
 *   and {@link \Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorStoragePublishListener} instead.
 *
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Communication\CmsBlockCategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 */
class CmsBlockCategoryConnectorPublishStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $this->preventTransaction();
        $idCategories = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventEntityTransfers);

        if ($eventName === CmsBlockCategoryConnectorEvents::CMS_BLOCK_CATEGORY_CONNECTOR_UNPUBLISH) {
            $this->getFacade()->refreshOrUnpublish($idCategories);

            return;
        }

        $this->getFacade()->publish($idCategories);
    }
}
