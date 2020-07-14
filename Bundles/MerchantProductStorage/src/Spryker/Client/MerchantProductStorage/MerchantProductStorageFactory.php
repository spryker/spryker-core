<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToLocaleClientInterface;
use Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToProductStorageClientInterface;
use Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapper;
use Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapperInterface;
use Spryker\Client\MerchantProductStorage\Reader\MerchantProductStorageReader;
use Spryker\Client\MerchantProductStorage\Reader\MerchantProductStorageReaderInterface;

class MerchantProductStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantProductStorage\Reader\MerchantProductStorageReaderInterface
     */
    public function createMerchantProductStorageReader(): MerchantProductStorageReaderInterface
    {
        return new MerchantProductStorageReader(
            $this->getProductStorageClient(),
            $this->getLocaleClient(),
            $this->createMerchantProductStorageMapper()
        );
    }

    /**
     * @return \Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapperInterface
     */
    public function createMerchantProductStorageMapper(): MerchantProductStorageMapperInterface
    {
        return new MerchantProductStorageMapper();
    }

    /**
     * @return \Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToProductStorageClientInterface
     */
    public function getProductStorageClient(): MerchantProductStorageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductStorageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToLocaleClientInterface
     */
    public function getLocaleClient(): MerchantProductStorageToLocaleClientInterface
    {
        return $this->getProvidedDependency(MerchantProductStorageDependencyProvider::CLIENT_LOCALE);
    }
}
