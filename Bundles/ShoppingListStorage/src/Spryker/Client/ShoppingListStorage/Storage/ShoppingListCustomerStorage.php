<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage\Storage;

use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageInterface;
use Spryker\Client\ShoppingListStorage\KeyBuilder\ShoppingListStorageKeyBuilder;

class ShoppingListCustomerStorage implements ShoppingListCustomerStorageInterface
{
    /**
     * @var \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageInterface
     */
    protected $storage;

    /**
     * @var \Spryker\Client\ShoppingListStorage\KeyBuilder\ShoppingListStorageKeyBuilder
     */
    protected $keyBuilder;

    /**
     * @var string
     */
    protected $locale;

    /**
     * ShoppingListStorage constructor.
     *
     * @param \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageInterface $storage
     * @param \Spryker\Client\ShoppingListStorage\KeyBuilder\ShoppingListStorageKeyBuilder $keyBuilder
     * @param string $locale
     */
    public function __construct(
        ShoppingListStorageToStorageInterface $storage,
        ShoppingListStorageKeyBuilder $keyBuilder,
        string $locale
    ) {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->locale = $locale;
    }

    /**
     * @param string $customerReference
     *
     * @return mixed
     */
    public function getShoppingListCustomerStorageByCustomerReference(string $customerReference)
    {
        $key = $this->keyBuilder->generateKey($customerReference, $this->locale);
        $shoppingList = $this->storage->get($key);

        return $shoppingList;
    }
}
