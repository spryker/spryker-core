<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business;

use Spryker\Zed\FileManager\Business\Model\FileContent;
use Spryker\Zed\FileManager\Business\Model\FileFinder;
use Spryker\Zed\FileManager\Business\Model\FileLocalizedAttributesSaver;
use Spryker\Zed\FileManager\Business\Model\FileReader;
use Spryker\Zed\FileManager\Business\Model\FileRemover;
use Spryker\Zed\FileManager\Business\Model\FileRollback;
use Spryker\Zed\FileManager\Business\Model\FileSaver;
use Spryker\Zed\FileManager\Business\Model\FileVersion;
use Spryker\Zed\FileManager\Business\Tree\FileDirectoryTreeHierarchyUpdater;
use Spryker\Zed\FileManager\Business\Tree\FileDirectoryTreeReader;
use Spryker\Zed\FileManager\FileManagerDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\FileManager\FileManagerConfig getConfig()
 */
class FileManagerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileSaverInterface
     */
    public function createFileSaver()
    {
        return new FileSaver(
            $this->getQueryContainer(),
            $this->createFileVersion(),
            $this->createFileFinder(),
            $this->createFileContent(),
            $this->createFileLocalizedAttributesSaver(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileRollbackInterface
     */
    public function createFileRollback()
    {
        return new FileRollback(
            $this->createFileFinder(),
            $this->createFileVersion()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileRemoverInterface
     */
    public function createFileRemover()
    {
        return new FileRemover(
            $this->createFileFinder(),
            $this->createFileContent(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileLocalizedAttributesSaverInterface
     */
    public function createFileLocalizedAttributesSaver()
    {
        return new FileLocalizedAttributesSaver();
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileReaderInterface
     */
    public function createFileReader()
    {
        return new FileReader(
            $this->createFileFinder(),
            $this->createFileContent()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileVersionInterface
     */
    public function createFileVersion()
    {
        return new FileVersion($this->createFileFinder());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileFinderInterface
     */
    public function createFileFinder()
    {
        return new FileFinder(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileContentInterface
     */
    public function createFileContent()
    {
        return new FileContent(
            $this->getFileSystemService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface
     */
    public function getFileSystemService()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::SERVICE_FILE_SYSTEM);
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Tree\FileDirectoryTreeReaderInterface
     */
    public function createFileDirectoryTreeReader()
    {
        return new FileDirectoryTreeReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Tree\FileDirectoryTreeHierarchyUpdaterInterface
     */
    public function createFileDirectoryTreeHierarchyUpdater()
    {
        return new FileDirectoryTreeHierarchyUpdater($this->getQueryContainer());
    }
}
