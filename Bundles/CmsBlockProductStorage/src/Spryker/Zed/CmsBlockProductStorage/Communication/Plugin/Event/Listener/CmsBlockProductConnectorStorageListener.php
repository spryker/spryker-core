<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\CmsBlockProductConnector\Persistence\Map\SpyCmsBlockProductConnectorTableMap;
use Spryker\Zed\CmsBlockProductConnector\Dependency\CmsBlockProductConnectorEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\Communication\CmsBlockProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageFacadeInterface getFacade()
 */
class CmsBlockProductConnectorStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $idProductAbstracts = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyCmsBlockProductConnectorTableMap::COL_FK_PRODUCT_ABSTRACT);

        if ($eventName === CmsBlockProductConnectorEvents::ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_DELETE) {
            $this->getFacade()->refreshOrUnpublish($idProductAbstracts);

            return;
        }

        $this->getFacade()->publish($idProductAbstracts);
    }
}
