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
     * @var \Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToStorageClientInterface
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
    public function hasProductCustomerPermission(int $idCustomer, int $idProductAbstract): bool
    {
        $permission = $this->storageClient->get($this->getStorageKey($idCustomer, $idProductAbstract));

        return $permission !== null;
    }

    /**
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function getStorageKey(int $idCustomer, int $idProductAbstract): string
    {
        $identifier = $idProductAbstract . '.' . $idCustomer;
        $locale = $this->localeClient->getCurrentLocale();

        return $this->keyBuilder->generateKey($identifier, $locale);
    }
}
