<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager;

use Spryker\Service\FileManager\Model\Adapter\FileManager;
use Spryker\Service\Kernel\AbstractServiceFactory;

class FileManagerServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\FileManager\Model\Adapter\FileManager
     */
    public function createFileManagerAdapter()
    {
        return new FileManager($this->getFileManagerPlugin());
    }

    /**
     * @return \Spryker\Service\FileManager\Dependency\Plugin\FileManagerPluginInterface
     */
    protected function getFileManagerPlugin()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::FILE_MANAGER_PLUGIN);
    }
}
