<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShoppingListStorage\Business\ShoppingListCustomerPublisher\ShoppingListCustomerStoragePublisher;
use Spryker\Zed\ShoppingListStorage\Business\ShoppingListCustomerPublisher\ShoppingListCustomerStoragePublisherInterface;

class ShoppingListStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShoppingListStorage\Business\ShoppingListCustomerPublisher\ShoppingListCustomerStoragePublisherInterface
     */
    public function createShoppingListCustomerStoragePublisher(): ShoppingListCustomerStoragePublisherInterface
    {
        return new ShoppingListCustomerStoragePublisher(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }
}
