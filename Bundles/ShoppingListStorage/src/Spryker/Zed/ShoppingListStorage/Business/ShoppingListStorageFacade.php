<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @api
 *
 * @method \Spryker\Zed\ShoppingListStorage\Business\ShoppingListStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface getRepository()
 */
class ShoppingListStorageFacade extends AbstractFacade implements ShoppingListStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $customerReferences
     *
     * @return void
     */
    public function publish(array $customerReferences): void
    {
        $this->getFactory()->createShoppingListCustomerStoragePublisher()->publish($customerReferences);
    }
}
