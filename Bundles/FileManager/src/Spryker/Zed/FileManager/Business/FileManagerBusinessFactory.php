<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business;

use Spryker\Zed\FileManager\Business\File\FileReader;
use Spryker\Zed\FileManager\Business\File\FileRemover;
use Spryker\Zed\FileManager\Business\File\FileRollback;
use Spryker\Zed\FileManager\Business\File\FileSaver;
use Spryker\Zed\FileManager\Business\File\FileVersion;
use Spryker\Zed\FileManager\Business\FileContent\FileContent;
use Spryker\Zed\FileManager\Business\FileDirectory\FileDirectoryReader;
use Spryker\Zed\FileManager\Business\FileDirectory\FileDirectoryRemover;
use Spryker\Zed\FileManager\Business\FileDirectory\FileDirectorySaver;
use Spryker\Zed\FileManager\Business\FileDirectoryLocalizedAttributes\FileDirectoryLocalizedAttributesSaver;
use Spryker\Zed\FileManager\Business\FileDirectoryTree\FileDirectoryTreeHierarchyUpdater;
use Spryker\Zed\FileManager\Business\FileDirectoryTree\FileDirectoryTreeReader;
use Spryker\Zed\FileManager\Business\FileLocalizedAttributes\FileLocalizedAttributesSaver;
use Spryker\Zed\FileManager\Business\MimeType\MimeTypeReader;
use Spryker\Zed\FileManager\Business\MimeType\MimeTypeRemover;
use Spryker\Zed\FileManager\Business\MimeType\MimeTypeSaver;
use Spryker\Zed\FileManager\FileManagerDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FileManager\FileManagerConfig getConfig()
 */
class FileManagerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\FileManager\Business\File\FileSaverInterface
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
     * @return \Spryker\Zed\FileManager\Business\File\FileReaderInterface
     */
    public function createFileReader()
    {
        return new FileReader(
            $this->getRepository(),
            $this->createFileContent()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\File\FileRollbackInterface
     */
    public function createFileRollback()
    {
        return new FileRollback(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createFileVersion()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\File\FileRemoverInterface
     */
    public function createFileRemover()
    {
        return new FileRemover(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createFileContent()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileDirectory\FileDirectorySaverInterface
     */
    public function createFileDirectorySaver()
    {
        return new FileDirectorySaver(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createFileDirectoryLocalizedAttributesSaver()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileDirectory\FileDirectoryReaderInterface
     */
    public function createFileDirectoryReader()
    {
        return new FileDirectoryReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileDirectory\FileDirectoryRemoverInterface
     */
    public function createFileDirectoryRemover()
    {
        return new FileDirectoryRemover(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getFileSystemService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileLocalizedAttributes\FileLocalizedAttributesSaverInterface
     */
    public function createLocalizedAttributesSaver()
    {
        return new FileLocalizedAttributesSaver($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileDirectoryLocalizedAttributes\FileDirectoryLocalizedAttributesSaverInterface
     */
    public function createFileDirectoryLocalizedAttributesSaver()
    {
        return new FileDirectoryLocalizedAttributesSaver($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\File\FileVersionInterface
     */
    public function createFileVersion()
    {
        return new FileVersion($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileContent\FileContentInterface
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
     * @return \Spryker\Zed\FileManager\Business\FileDirectoryTree\FileDirectoryTreeReaderInterface
     */
    public function createFileDirectoryTreeReader()
    {
        return new FileDirectoryTreeReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileDirectoryTree\FileDirectoryTreeHierarchyUpdaterInterface
     */
    public function createFileDirectoryTreeHierarchyUpdater()
    {
        return new FileDirectoryTreeHierarchyUpdater(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\MimeType\MimeTypeSaverInterface
     */
    public function createMimeTypeSaver()
    {
        return new MimeTypeSaver(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\MimeType\MimeTypeRemoverInterface
     */
    public function createMimeTypeRemover()
    {
        return new MimeTypeRemover($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\MimeType\MimeTypeReaderInterface
     */
    public function createMimeTypeReader()
    {
        return new MimeTypeReader($this->getRepository());
    }
}
