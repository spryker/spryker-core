<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelStorage\Communication\ProductLabelStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface getFacade()
 */
class ProductLabelPublishStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
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
        $productAbstractIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventTransfers);

        $this->getFacade()->publishProductLabel($productAbstractIds);
    }
}
