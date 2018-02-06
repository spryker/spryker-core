<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCustomerPermission\Storage;

use Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToLocaleClientInterface;
use Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToStorageClientInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class ProductCustomerPermissionStorage implements ProductCustomerPermissionStorageInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var \Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToStorageClientInterface $storage
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param \Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToLocaleClientInterface $localeClient
     */
    public function __construct(
        ProductCustomerPermissionToStorageClientInterface $storage,
        KeyBuilderInterface $keyBuilder,
        ProductCustomerPermissionToLocaleClientInterface $localeClient
    ) {
        $this->storageClient = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->localeClient = $localeClient;
    }

    /**
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function hasProductCustomerPermission(int $idCustomer, int $idProductAbstract)
    {
        $identifier = $idProductAbstract . '.' . $idCustomer;
        $locale = $this->localeClient->getCurrentLocale();
        $key = $this->keyBuilder->generateKey($identifier, $locale);
        $permission = $this->storageClient->get($key);

        return $permission === null ? false : true;
    }
}
