<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Spryker\Zed\Dataset\Business\Model\DatasetColSaver;
use Spryker\Zed\Dataset\Business\Model\DatasetFinder;
use Spryker\Zed\Dataset\Business\Model\DatasetLocalizedAttributesSaver;
use Spryker\Zed\Dataset\Business\Model\DatasetRowColValueSaver;
use Spryker\Zed\Dataset\Business\Model\DatasetRowSaver;
use Spryker\Zed\Dataset\Business\Model\DatasetSaver;
use Spryker\Zed\Dataset\Business\Model\ReaderManager;
use Spryker\Zed\Dataset\DatasetDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Dataset\DatasetConfig getConfig()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface getQueryContainer()
 */
class DatasetBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetFinderInterface
     */
    public function createDatasetFinder()
    {
        return new DatasetFinder(
            $this->getQueryContainer(),
            $this->getProvidedDependency(DatasetDependencyProvider::FACADE_TOUCH)
        );
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetSaverInterface
     */
    public function createDatasetSaver()
    {
        return new DatasetSaver(
            $this->createDatasetFinder(),
            $this->createDatasetLocalizedAttributesSaver(),
            $this->createDatasetRowColValueSaver(),
            $this->createReaderManager()
        );
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetLocalizedAttributesSaverInterface
     */
    public function createDatasetLocalizedAttributesSaver()
    {
        return new DatasetLocalizedAttributesSaver();
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetColSaverInterface
     */
    public function createDatasetColSaver()
    {
        return new DatasetColSaver($this->createDatasetFinder());
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetRowSaverInterface
     */
    public function createDatasetRowSaver()
    {
        return new DatasetRowSaver($this->createDatasetFinder());
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetRowColValueSaverInterface
     */
    public function createDatasetRowColValueSaver()
    {
        return new DatasetRowColValueSaver(
            $this->createDatasetColSaver(),
            $this->createDatasetRowSaver()
        );
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\ReaderManagerInterface
     */
    public function createReaderManager()
    {
        return new ReaderManager();
    }
}
