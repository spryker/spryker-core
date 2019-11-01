<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\Communication\MerchantProfileStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig getConfig()
 */
class MerchantProfileStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use TransactionTrait;

    /**
     * {@inheritDoc}
     * - Handles merchant profile delete events.
     *
     * @api
     *
     * @param array $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $merchantProfileIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
        $this->getTransactionHandler()->handleTransaction(function () use ($merchantProfileIds): void {
            $this->getFacade()->unpublish($merchantProfileIds);
        });
    }
}
