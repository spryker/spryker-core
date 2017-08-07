<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemAws3v3FileSystem;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Filesystem\Aws3v3FilesystemBuilder;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\FlysystemAws3v3FileSystem\FlysystemAws3v3FileSystemConfig getConfig()
 */
class FlysystemAws3v3FileSystemServiceFactory extends AbstractServiceFactory
{

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     * @param \League\Flysystem\PluginInterface[] $flysystemPluginCollection
     *
     * @return \Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Filesystem\Aws3v3FilesystemBuilder
     */
    public function createFlysystemAws3v3FileSystemBuilder(FlysystemConfigTransfer $configTransfer, array $flysystemPluginCollection = [])
    {
        return new Aws3v3FilesystemBuilder($configTransfer);
    }

}
