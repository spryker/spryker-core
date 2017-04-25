<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business;

use Spryker\Zed\FileSystem\Business\Model\FileSystemHandler;
use Spryker\Zed\FileSystem\FileSystemDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FileSystem\FileSystemConfig getConfig()
 */
class FileSystemBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\FileSystem\Business\Model\FileSystemHandlerInterface
     */
    public function createFileSystemHandler()
    {
        return new FileSystemHandler(
            $this->getFlysystemService()
        );
    }

    /**
     * @return \Spryker\Zed\FileSystem\Dependency\Service\FileSystemToFlysystemInterface
     */
    protected function getFlysystemService()
    {
        return $this->getProvidedDependency(FileSystemDependencyProvider::SERVICE_FLYSYSTEM);
    }

}
