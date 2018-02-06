<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Spryker\Zed\Dataset\Business\Model\DatasetColumnSaver;
use Spryker\Zed\Dataset\Business\Model\DatasetFinder;
use Spryker\Zed\Dataset\Business\Model\DatasetLocalizedAttributesSaver;
use Spryker\Zed\Dataset\Business\Model\DatasetRowColumnValueSaver;
use Spryker\Zed\Dataset\Business\Model\DatasetRowSaver;
use Spryker\Zed\Dataset\Business\Model\DatasetSaver;
use Spryker\Zed\Dataset\Business\Model\ReaderManager;
use Spryker\Zed\Dataset\Business\Model\WriterManager;
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
        return new DatasetFinder($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetSaverInterface
     */
    public function createDatasetSaver()
    {
        return new DatasetSaver(
            $this->createDatasetFinder(),
            $this->createDatasetLocalizedAttributesSaver(),
            $this->createDatasetRowColumnValueSaver(),
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
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetColumnSaverInterface
     */
    public function createDatasetColumnSaver()
    {
        return new DatasetColumnSaver($this->createDatasetFinder());
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetRowSaverInterface
     */
    public function createDatasetRowSaver()
    {
        return new DatasetRowSaver($this->createDatasetFinder());
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetRowColumnValueSaverInterface
     */
    public function createDatasetRowColumnValueSaver()
    {
        return new DatasetRowColumnValueSaver(
            $this->createDatasetColumnSaver(),
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

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\WriterManagerInterface
     */
    public function createWriterManager()
    {
        return new WriterManager($this->createDatasetFinder());
    }
}
