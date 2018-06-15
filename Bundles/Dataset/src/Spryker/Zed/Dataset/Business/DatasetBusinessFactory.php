<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Spryker\Zed\Dataset\Business\Finder\DatasetFinder;
use Spryker\Zed\Dataset\Business\Finder\DatasetFinderInterface;
use Spryker\Zed\Dataset\Business\Saver\DatasetSaver;
use Spryker\Zed\Dataset\Business\Saver\DatasetSaverInterface;
use Spryker\Zed\Dataset\Business\Reader\Reader;
use Spryker\Zed\Dataset\Business\Reader\ReaderInterface;
use Spryker\Zed\Dataset\Business\Resolver\ResolverPath;
use Spryker\Zed\Dataset\Business\Resolver\ResolverPathInterface;
use Spryker\Zed\Dataset\Business\Writer\Writer;
use Spryker\Zed\Dataset\Business\Writer\WriterInterface;
use Spryker\Zed\Dataset\DatasetDependencyProvider;
use Spryker\Zed\Dataset\Dependency\Service\DatasetToCsvBridge;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface getRepository()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Dataset\DatasetConfig getConfig()
 */
class DatasetBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Dataset\Business\Finder\DatasetFinderInterface
     */
    public function createDatasetFinder(): DatasetFinderInterface
    {
        return new DatasetFinder(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Finder\DatasetSaverInterface
     */
    public function createDatasetSaver(): DatasetSaverInterface
    {
        return new DatasetSaver(
            $this->getEntityManager(),
            $this->createReader()
        );
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Finder\ReaderInterface
     */
    public function createReader(): ReaderInterface
    {
        return new Reader($this->getDatasetToCsvBridge());
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Finder\WriterInterface
     */
    public function createWriter(): WriterInterface
    {
        return new Writer($this->getDatasetToCsvBridge());
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Finder\ResolverPathInterface
     */
    public function createResolverPath(): ResolverPathInterface
    {
        return new ResolverPath();
    }

    /**
     * @return \Spryker\Zed\Dataset\Dependency\Service\DatasetToCsvBridge
     */
    public function getDatasetToCsvBridge(): DatasetToCsvBridge
    {
        return $this->getProvidedDependency(DatasetDependencyProvider::DATASET_TO_CSV_BRIDGE);
    }
}
