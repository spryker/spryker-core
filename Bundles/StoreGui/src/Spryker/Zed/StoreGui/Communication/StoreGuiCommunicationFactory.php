<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StoreGui\Communication\Form\DataProvider\StoreRelationDropdownDataProvider;
use Spryker\Zed\StoreGui\Communication\Form\Transformer\IdStoresDataTransformer;
use Spryker\Zed\StoreGui\Communication\Table\StoreTable;
use Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface;
use Spryker\Zed\StoreGui\Dependency\Service\StoreGuiToUtilEncodingServiceInterface;
use Spryker\Zed\StoreGui\StoreGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;

class StoreGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StoreGui\Communication\Table\StoreTable
     */
    public function createStoreTable(): StoreTable
    {
        return new StoreTable($this->getStorePropelQuery());
    }

    /**
     * @return \Spryker\Zed\StoreGui\Communication\Form\DataProvider\StoreRelationDropdownDataProvider
     */
    public function createStoreRelationDropdownDataProvider(): StoreRelationDropdownDataProvider
    {
        return new StoreRelationDropdownDataProvider($this->getStoreFacade());
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createIdStoresDataTransformer(): DataTransformerInterface
    {
        return new IdStoresDataTransformer();
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::PROPEL_QUERY_STORE);
    }

    /**
     * @return \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): StoreGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\StoreGui\Dependency\Service\StoreGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): StoreGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
