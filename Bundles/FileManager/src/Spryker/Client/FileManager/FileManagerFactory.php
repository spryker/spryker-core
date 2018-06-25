<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManager;

use Spryker\Client\FileManager\Model\FileReader;
use Spryker\Client\Kernel\AbstractFactory;

class FileManagerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\FileManager\Model\FileReaderInterface
     */
    public function createFileReader()
    {
        return new FileReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getLocaleClient()
        );
    }

    /**
     * @return \Spryker\Client\FileManager\Dependency\Client\FileManagerToSynchronizationServiceInterface
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\FileManager\Dependency\Client\FileManagerToStorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\FileManager\Dependency\Client\FileManagerToLocaleClientInterface
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::CLIENT_LOCALE);
    }
}
