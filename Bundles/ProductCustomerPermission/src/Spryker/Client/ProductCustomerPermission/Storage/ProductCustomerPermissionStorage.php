<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCustomerPermission\Storage;

use Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToStorageClientInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class ProductCustomerPermissionStorage implements ProductCustomerPermissionStorageInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    private $storageClient;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    private $keyBuilder;

    /**
     * @var string
     */
    private $locale;

    /**
     * @param \Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToStorageClientInterface $storage
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param string $localeName
     */
    public function __construct(ProductCustomerPermissionToStorageClientInterface $storage, KeyBuilderInterface $keyBuilder, $localeName)
    {
        $this->storageClient = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->locale = $localeName;
    }

    /**
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function getProductCustomerPermission(int $idCustomer, int $idProductAbstract)
    {
        $identifier = $idProductAbstract . '.' . $idCustomer;
        $key = $this->keyBuilder->generateKey($identifier, $this->locale);
        $permission = $this->storageClient->get($key);

        return $permission === null ? false : true;
    }
}
