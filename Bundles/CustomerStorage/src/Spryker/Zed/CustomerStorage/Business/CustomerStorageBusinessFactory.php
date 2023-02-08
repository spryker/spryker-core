<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Business;

use Spryker\Zed\CustomerStorage\Business\Deleter\CustomerStorageDeleter;
use Spryker\Zed\CustomerStorage\Business\Deleter\CustomerStorageDeleterInterface;
use Spryker\Zed\CustomerStorage\Business\Mapper\CustomerStorageMapper;
use Spryker\Zed\CustomerStorage\Business\Mapper\CustomerStorageMapperInterface;
use Spryker\Zed\CustomerStorage\Business\Reader\CustomerStorageReader;
use Spryker\Zed\CustomerStorage\Business\Reader\CustomerStorageReaderInterface;
use Spryker\Zed\CustomerStorage\Business\Writer\CustomerStorageWriter;
use Spryker\Zed\CustomerStorage\Business\Writer\CustomerStorageWriterInterface;
use Spryker\Zed\CustomerStorage\CustomerStorageDependencyProvider;
use Spryker\Zed\CustomerStorage\Dependency\Facade\CustomerStorageToCustomerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerStorage\CustomerStorageConfig getConfig()
 */
class CustomerStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerStorage\Business\Writer\CustomerStorageWriterInterface
     */
    public function createCustomersStorageWriter(): CustomerStorageWriterInterface
    {
        return new CustomerStorageWriter(
            $this->createCustomersStorageMapper(),
            $this->getCustomerFacade(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\CustomerStorage\Business\Reader\CustomerStorageReaderInterface
     */
    public function createCustomersStorageReader(): CustomerStorageReaderInterface
    {
        return new CustomerStorageReader(
            $this->createCustomersStorageMapper(),
            $this->getCustomerFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\CustomerStorage\Business\Deleter\CustomerStorageDeleterInterface
     */
    public function createCustomersStorageDeleter(): CustomerStorageDeleterInterface
    {
        return new CustomerStorageDeleter(
            $this->getEntityManager(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\CustomerStorage\Business\Mapper\CustomerStorageMapperInterface
     */
    public function createCustomersStorageMapper(): CustomerStorageMapperInterface
    {
        return new CustomerStorageMapper();
    }

    /**
     * @return \Spryker\Zed\CustomerStorage\Dependency\Facade\CustomerStorageToCustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomerStorageToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(CustomerStorageDependencyProvider::FACADE_CUSTOMER);
    }
}
