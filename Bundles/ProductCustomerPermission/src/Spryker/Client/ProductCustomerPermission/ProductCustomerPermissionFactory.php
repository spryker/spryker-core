<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCustomerPermission;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductCustomerPermission\KeyBuilder\ProductCustomerPermissionResourceKeyBuilder;
use Spryker\Client\ProductCustomerPermission\Storage\ProductCustomerPermissionStorage;

class ProductCustomerPermissionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductCustomerPermission\Storage\ProductCustomerPermissionStorageInterface
     */
    public function createStorage(): Storage\ProductCustomerPermissionStorageInterface
    {
        return new ProductCustomerPermissionStorage(
            $this->getStorageClient(),
            $this->createKeyBuilder(),
            $this->getLocaleClient()
        );
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder(): \Spryker\Shared\KeyBuilder\KeyBuilderInterface
    {
        return new ProductCustomerPermissionResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToCustomerClientInterface
     */
    public function getCustomerClient(): Dependency\Client\ProductCustomerPermissionToCustomerClientInterface
    {
        return $this->getProvidedDependency(ProductCustomerPermissionDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToStorageClientInterface
     */
    public function getStorageClient(): Dependency\Client\ProductCustomerPermissionToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductCustomerPermissionDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToLocaleClientInterface
     */
    public function getLocaleClient(): Dependency\Client\ProductCustomerPermissionToLocaleClientInterface
    {
        return $this->getProvidedDependency(ProductCustomerPermissionDependencyProvider::CLIENT_LOCALE);
    }
}
