<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business;

use Spryker\Zed\FileManager\Business\Model\FileContent;
use Spryker\Zed\FileManager\Business\Model\FileDirectoryLocalizedAttributesSaver;
use Spryker\Zed\FileManager\Business\Model\FileDirectoryRemover;
use Spryker\Zed\FileManager\Business\Model\FileDirectorySaver;
use Spryker\Zed\FileManager\Business\Model\FileLocalizedAttributesSaver;
use Spryker\Zed\FileManager\Business\Model\FileReader;
use Spryker\Zed\FileManager\Business\Model\FileRemover;
use Spryker\Zed\FileManager\Business\Model\FileRollback;
use Spryker\Zed\FileManager\Business\Model\FileSaver;
use Spryker\Zed\FileManager\Business\Model\FileVersion;
use Spryker\Zed\FileManager\Business\Model\MimeTypeReader;
use Spryker\Zed\FileManager\Business\Model\MimeTypeRemover;
use Spryker\Zed\FileManager\Business\Model\MimeTypeSaver;
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
            $this->getEntityManager(),
            $this->createFileVersion(),
            $this->createFileContent(),
            $this->createLocalizedAttributesSaver(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileDirectorySaverInterface
     */
    public function createFileDirectorySaver()
    {
        return new FileDirectorySaver(
            $this->getQueryContainer(),
            $this->createFileLoader(),
            $this->createFileDirectoryLocalizedAttributesSaver()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileRollbackInterface
     */
    public function createFileRollback()
    {
        return new FileRollback(
            $this->createFileLoader(),
            $this->createFileVersion()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileRemoverInterface
     */
    public function createFileRemover()
    {
        return new FileRemover(
            $this->createFileLoader(),
            $this->createFileContent(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileDirectoryRemoverInterface
     */
    public function createFileDirectoryRemover()
    {
        return new FileDirectoryRemover(
            $this->getQueryContainer(),
            $this->createFileLoader(),
            $this->getFileSystemService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileLocalizedAttributesSaverInterface
     */
    public function createLocalizedAttributesSaver()
    {
        return new FileLocalizedAttributesSaver($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileDirectoryLocalizedAttributesSaverInterface
     */
    public function createFileDirectoryLocalizedAttributesSaver()
    {
        return new FileDirectoryLocalizedAttributesSaver();
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileReaderInterface
     */
    public function createFileReader()
    {
        return new FileReader(
            $this->getRepository(),
            $this->createFileContent()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileVersionInterface
     */
    public function createFileVersion()
    {
        return new FileVersion($this->getRepository());
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

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\MimeTypeSaverInterface
     */
    public function createMimeTypeSaver()
    {
        return new MimeTypeSaver(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\MimeTypeRemoverInterface
     */
    public function createMimeTypeRemover()
    {
        return new MimeTypeRemover($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\MimeTypeReaderInterface
     */
    public function createMimeTypeReader()
    {
        return new MimeTypeReader($this->getRepository());
    }
}
