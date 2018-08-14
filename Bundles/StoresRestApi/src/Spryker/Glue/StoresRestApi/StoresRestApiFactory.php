<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi;

use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientInterface;
use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCurrencyClientInterface;
use Spryker\Glue\StoresRestApi\Processor\Stores\StoresReader;
use Spryker\Glue\StoresRestApi\Processor\Stores\StoresReaderInterface;
use Spryker\Glue\StoresRestApi\Processor\Stores\StoresCountryReader;
use Spryker\Glue\StoresRestApi\Processor\Stores\StoresCountryReaderInterface;
use Spryker\Glue\StoresRestApi\Processor\Stores\StoresCurrencyReader;
use Spryker\Glue\StoresRestApi\Processor\Stores\StoresCurrencyReaderInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapper;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapperInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCurrencyResourceMapper;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCurrencyResourceMapperInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresResourceMapper;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresResourceMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\Store;

class StoresRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientInterface
     */
    public function getCountryClient(): StoresRestApiToCountryClientInterface
    {
        return $this->getProvidedDependency(StoresRestApiDependencyProvider::CLIENT_COUNTRY);
    }

    /**
     * @return \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCurrencyClientInterface
     */
    public function getCurrencyClient(): StoresRestApiToCurrencyClientInterface
    {
        return $this->getProvidedDependency(StoresRestApiDependencyProvider::CLIENT_CURRENCY);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(StoresRestApiDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Glue\StoresRestApi\Processor\Stores\StoresReaderInterface
     */
    public function createStoresReader(): StoresReaderInterface
    {
        return new StoresReader(
            $this->createStoresCountryReader(),
            $this->createStoresCurrencyReader(),
            $this->getResourceBuilder(),
            $this->createStoresResourceMapper(),
            $this->getStore(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\StoresRestApi\Processor\Stores\StoresCountryReaderInterface
     */
    public function createStoresCountryReader(): StoresCountryReaderInterface
    {
        return new StoresCountryReader(
            $this->getCountryClient(),
            $this->getResourceBuilder(),
            $this->createStoresCountryResourceMapper(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Glue\StoresRestApi\Processor\Stores\StoresCurrencyReaderInterface
     */
    public function createStoresCurrencyReader(): StoresCurrencyReaderInterface
    {
        return new StoresCurrencyReader(
            $this->getCurrencyClient(),
            $this->getResourceBuilder(),
            $this->createStoresCurrencyResourceMapper(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresResourceMapperInterface
     */
    public function createStoresResourceMapper(): StoresResourceMapperInterface
    {
        return new StoresResourceMapper();
    }

    /**
     * @return \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCountryResourceMapperInterface
     */
    public function createStoresCountryResourceMapper(): StoresCountryResourceMapperInterface
    {
        return new StoresCountryResourceMapper();
    }

    /**
     * @return \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCurrencyResourceMapperInterface
     */
    public function createStoresCurrencyResourceMapper(): StoresCurrencyResourceMapperInterface
    {
        return new StoresCurrencyResourceMapper();
    }
}
