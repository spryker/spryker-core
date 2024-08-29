<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrix\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OrderMatrix\Business\Indexer\OrderMatrixIndexer;
use Spryker\Zed\OrderMatrix\Business\Indexer\OrderMatrixIndexerInterface;
use Spryker\Zed\OrderMatrix\Business\Reader\OrderMatrixReader;
use Spryker\Zed\OrderMatrix\Business\Reader\OrderMatrixReaderInterface;
use Spryker\Zed\OrderMatrix\Business\Reader\OrderMatrixStatisticsReader;
use Spryker\Zed\OrderMatrix\Business\Reader\OrderMatrixStatisticsReaderInterface;
use Spryker\Zed\OrderMatrix\Business\Writer\OrderMatrixWriter;
use Spryker\Zed\OrderMatrix\Business\Writer\OrderMatrixWriterInterface;
use Spryker\Zed\OrderMatrix\Dependency\Client\OrderMatrixToStorageRedisClientInterface;
use Spryker\Zed\OrderMatrix\Dependency\Facade\OrderMatrixToOmsFacadeInterface;
use Spryker\Zed\OrderMatrix\Dependency\Service\OrderMatrixToUtilEncodingServiceInterface;
use Spryker\Zed\OrderMatrix\OrderMatrixDependencyProvider;

/**
 * @method \Spryker\Zed\OrderMatrix\OrderMatrixConfig getConfig()
 * @method \Spryker\Zed\OrderMatrix\Persistence\OrderMatrixEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\OrderMatrix\Persistence\OrderMatrixRepositoryInterface getRepository()
 */
class OrderMatrixBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OrderMatrix\Dependency\Facade\OrderMatrixToOmsFacadeInterface
     */
    public function getOmsFacade(): OrderMatrixToOmsFacadeInterface
    {
        return $this->getProvidedDependency(OrderMatrixDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\OrderMatrix\Dependency\Client\OrderMatrixToStorageRedisClientInterface
     */
    public function getStorageRedisClient(): OrderMatrixToStorageRedisClientInterface
    {
        return $this->getProvidedDependency(OrderMatrixDependencyProvider::CLIENT_STORAGE_REDIS);
    }

    /**
     * @return \Spryker\Zed\OrderMatrix\Dependency\Service\OrderMatrixToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OrderMatrixToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OrderMatrixDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\OrderMatrix\Business\Writer\OrderMatrixWriterInterface
     */
    public function createOrderMatrixWriter(): OrderMatrixWriterInterface
    {
        return new OrderMatrixWriter(
            $this->createOrderMatrixReader(),
            $this->createOrderMatrixIndexer(),
            $this->getStorageRedisClient(),
            $this->getUtilEncodingService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\OrderMatrix\Business\Reader\OrderMatrixReaderInterface
     */
    public function createOrderMatrixReader(): OrderMatrixReaderInterface
    {
        return new OrderMatrixReader(
            $this->getConfig(),
            $this->getOmsFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\OrderMatrix\Business\Indexer\OrderMatrixIndexerInterface
     */
    public function createOrderMatrixIndexer(): OrderMatrixIndexerInterface
    {
        return new OrderMatrixIndexer();
    }

    /**
     * @return \Spryker\Zed\OrderMatrix\Business\Reader\OrderMatrixStatisticsReaderInterface
     */
    public function createOrderMatrixStatisticsReader(): OrderMatrixStatisticsReaderInterface
    {
        return new OrderMatrixStatisticsReader(
            $this->getStorageRedisClient(),
            $this->getConfig(),
        );
    }
}
