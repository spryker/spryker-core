<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManager;

use Spryker\Client\FileManager\Model\FileReader;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Service\Synchronization\Model\KeyFilter;
use Spryker\Service\Synchronization\Plugin\DefaultKeyGeneratorPlugin;

class FileManagerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\FileManager\Model\FileReaderInterface
     */
    public function createFileReader()
    {
        return new FileReader(
            $this->getStorageClient(),
            $this->createKeyGenerator(),
            $this->getLocaleClient()
        );
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function createKeyGenerator()
    {
        return new DefaultKeyGeneratorPlugin(
            $this->createKeyFilter()
        );
    }

    /**
     * @return \Spryker\Service\Synchronization\Model\KeyFilterInterface
     */
    protected function createKeyFilter()
    {
        return new KeyFilter();
    }

    /**
     * @return \Spryker\Client\FileManager\Dependency\Client\FileManagerToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\FileManager\Dependency\Client\FileManagerToLocaleClientInterface
     */
    protected function getLocaleClient()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::CLIENT_LOCALE);
    }
}
