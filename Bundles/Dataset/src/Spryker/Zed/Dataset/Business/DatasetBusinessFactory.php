<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Spryker\Zed\Dataset\Business\Model\DatasetFinder;
use Spryker\Zed\Dataset\Business\Model\DatasetFinderInterface;
use Spryker\Zed\Dataset\Business\Model\DatasetSaver;
use Spryker\Zed\Dataset\Business\Model\DatasetSaverInterface;
use Spryker\Zed\Dataset\Business\Model\Reader;
use Spryker\Zed\Dataset\Business\Model\ReaderInterface;
use Spryker\Zed\Dataset\Business\Model\ResolverPath;
use Spryker\Zed\Dataset\Business\Model\ResolverPathInterface;
use Spryker\Zed\Dataset\Business\Model\Writer;
use Spryker\Zed\Dataset\Business\Model\WriterInterface;
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
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetFinderInterface
     */
    public function createDatasetFinder(): DatasetFinderInterface
    {
        return new DatasetFinder(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetSaverInterface
     */
    public function createDatasetSaver(): DatasetSaverInterface
    {
        return new DatasetSaver(
            $this->getEntityManager(),
            $this->createReader()
        );
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\ReaderInterface
     */
    public function createReader(): ReaderInterface
    {
        return new Reader($this->getDatasetToCsvBridge());
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\WriterInterface
     */
    public function createWriter(): WriterInterface
    {
        return new Writer($this->getDatasetToCsvBridge());
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\ResolverPathInterface
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
