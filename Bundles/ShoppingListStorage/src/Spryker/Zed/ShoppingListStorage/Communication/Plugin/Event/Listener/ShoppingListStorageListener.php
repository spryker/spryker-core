<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ShoppingListStorage\Business\ShoppingListStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingListStorage\Communication\ShoppingListStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepository getRepository()
 */
class ShoppingListStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritdoc}
     *  - Handles shipping list create and update events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $this->preventTransaction();

        $shoppingListIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
        $customerReferences = $this->getRepository()->getCustomerReferencesByShoppingListIds($shoppingListIds);
        $customerReferences = array_unique($customerReferences);

        $this->getFacade()->publish($customerReferences);
    }
}
