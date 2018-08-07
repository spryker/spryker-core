<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business\Model;

class ShoppingListCustomerStorageWriter implements ShoppingListCustomerStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageEntityManager
     */
    protected $getEntityManager;

    /**
     * ShoppingListCustomerStorageWriter constructor.
     *
     * @param \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageEntityManager $getEntityManager
     */
    public function __construct($getEntityManager)
    {
        $this->getEntityManager = $getEntityManager;
    }

    /**
     * @param array $customerReferences
     *
     * @return void
     */
    public function publish(array $customerReferences): void
    {
        foreach ($customerReferences as $customerReference) {
            $this->getEntityManager->saveShoppingListCustomerStorage($customerReference);
        }
    }
}
