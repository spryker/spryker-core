<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Spryker\Zed\Dataset\Business\Model\DatasetFinder;
use Spryker\Zed\Dataset\Business\Model\DatasetSaver;
use Spryker\Zed\Dataset\Business\Model\Downloader;
use Spryker\Zed\Dataset\Business\Model\Reader;
use Spryker\Zed\Dataset\Business\Model\Writer;
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
    public function createDatasetFinder()
    {
        return new DatasetFinder(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetSaverInterface
     */
    public function createDatasetSaver()
    {
        return new DatasetSaver(
            $this->getEntityManager(),
            $this->createReader()
        );
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\ReaderInterface
     */
    public function createReader()
    {
        return new Reader();
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\WriterInterface
     */
    public function createWriter()
    {
        return new Writer($this->createDatasetFinder());
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DownloaderInterface
     */
    public function createDownloader()
    {
        return new Downloader();
    }
}
