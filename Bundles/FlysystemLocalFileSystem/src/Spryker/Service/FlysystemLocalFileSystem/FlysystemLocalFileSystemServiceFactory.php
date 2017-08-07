<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemLocalFileSystem;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Spryker\Service\FlysystemLocalFileSystem\Model\Builder\Filesystem\LocalFilesystemBuilder;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\FlysystemLocalFileSystem\FlysystemLocalFileSystemConfig getConfig()
 */
class FlysystemLocalFileSystemServiceFactory extends AbstractServiceFactory
{

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     * @param \League\Flysystem\PluginInterface[] $flysystemPluginCollection
     *
     * @return \Spryker\Service\FlysystemLocalFileSystem\Model\Builder\Filesystem\LocalFilesystemBuilder
     */
    public function createFlysystemLocalFileSystemBuilder(FlysystemConfigTransfer $configTransfer, array $flysystemPluginCollection = [])
    {
        return new LocalFilesystemBuilder($configTransfer);
    }

}
