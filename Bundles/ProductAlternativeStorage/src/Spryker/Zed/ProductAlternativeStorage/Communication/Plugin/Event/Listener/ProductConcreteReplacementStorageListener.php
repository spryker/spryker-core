<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAlternativeStorage\Communication\ProductAlternativeStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig getConfig()
 */
class ProductConcreteReplacementStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
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

        $productConcreteIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);

        if (empty($productConcreteIds)) {
            return;
        }

        $this->getFacade()->publishConcreteReplacements($productConcreteIds);
    }
}
