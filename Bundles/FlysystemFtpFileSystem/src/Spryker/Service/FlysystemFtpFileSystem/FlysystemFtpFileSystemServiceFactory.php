<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemFtpFileSystem;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Spryker\Service\FlysystemFtpFileSystem\Model\Builder\Filesystem\FtpFilesystemBuilder;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\FlysystemFtpFileSystem\FlysystemFtpFileSystemConfig getConfig()
 */
class FlysystemFtpFileSystemServiceFactory extends AbstractServiceFactory
{
    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     * @param \League\Flysystem\PluginInterface[] $flysystemPluginCollection
     *
     * @return \Spryker\Service\FlysystemFtpFileSystem\Model\Builder\Filesystem\FtpFilesystemBuilder
     */
    public function createFlysystemFtpFileSystemBuilder(FlysystemConfigTransfer $configTransfer, array $flysystemPluginCollection = [])
    {
        return new FtpFilesystemBuilder($configTransfer);
    }
}
