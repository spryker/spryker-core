<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager;

use Spryker\Service\FileManager\Model\FileReader;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\FileManager\FileManagerConfig getConfig()
 */
class FileManagerServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\FileManager\Model\FileReaderInterface
     */
    public function createFileReader()
    {
        return new FileReader($this->getFileSystemService(), $this->getConfig());
    }

    /**
     * @return \Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface
     */
    protected function getFileSystemService()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::FILE_SYSTEM_SERVICE);
    }
}
