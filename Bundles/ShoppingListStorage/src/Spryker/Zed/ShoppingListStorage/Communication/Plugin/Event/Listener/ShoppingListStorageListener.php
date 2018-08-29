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
use Spryker\Zed\ShoppingList\Dependency\ShoppingListEvents;

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
        if ($eventName === ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_CREATE) {
            $shoppingListIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
            $customerReferences = $this->getFacade()->getCustomerReferencesByShoppingListIds($shoppingListIds);
        } elseif ($eventName === ShoppingListEvents::CUSTOM_SHOPPING_LIST_DELETE) {
            foreach ($eventTransfers as $eventTransfer) {
                $modifiedColumns = $eventTransfer->getModifiedColumns();
                if (array_key_exists(SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE, $modifiedColumns)) {
                    $customerReferences[] = $modifiedColumns[SpyShoppingListTableMap::COL_CUSTOMER_REFERENCE];
                }
            }
        }
        $customerReferences = array_unique($customerReferences);
        $this->getFacade()->publish($customerReferences);
    }
}
