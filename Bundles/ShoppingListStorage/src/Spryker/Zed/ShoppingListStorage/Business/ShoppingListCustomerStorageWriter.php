<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business;

class ShoppingListCustomerStorageWriter
{
    /**
     * @var \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageEntityManager
     */
    protected $getEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepository
     */
    protected $getRepository;

    /**
     * ShoppingListCustomerStorageWriter constructor.
     *
     * @param \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageEntityManager $getEntityManager
     * @param \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepository $getRepository
     */
    public function __construct($getEntityManager, $getRepository)
    {
        $this->getEntityManager = $getEntityManager;
        $this->getRepository = $getRepository;
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
