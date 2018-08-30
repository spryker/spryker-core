<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ShoppingListStorage\Business\ShoppingListStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingListStorage\Communication\ShoppingListStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepository getRepository()
 */
class ShoppingListStorageCustomListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritdoc}
     *
     *  - Handles custom Delete event, that unlike of regular contains needed data in ModifiedColumns, uses this data
     *    for Publish.
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
        $customerReferences = [];

        $validEventTransfers = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransfersByModifiedColumns($eventTransfers, [
                SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE,
            ]);

        foreach ($validEventTransfers as $eventTransfer) {
            $customerReferences[] = $eventTransfer->getModifiedColumns()[SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE];
        }

        $customerReferences = array_unique($customerReferences);
        $this->getFacade()->publish($customerReferences);
    }
}
