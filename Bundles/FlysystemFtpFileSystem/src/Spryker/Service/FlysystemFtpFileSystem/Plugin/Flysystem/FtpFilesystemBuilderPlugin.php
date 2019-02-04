<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemFtpFileSystem\Plugin\Flysystem;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Spryker\Service\Flysystem\Dependency\Plugin\FlysystemFilesystemBuilderPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Service\FlysystemFtpFileSystem\FlysystemFtpFileSystemServiceFactory getFactory()
 */
class FtpFilesystemBuilderPlugin extends AbstractPlugin implements FlysystemFilesystemBuilderPluginInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function acceptType($type)
    {
        return $type === static::class;
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     * @param \League\Flysystem\PluginInterface[] $flysystemPluginCollection
     *
     * @return \League\Flysystem\Filesystem
     */
    public function build(FlysystemConfigTransfer $configTransfer, array $flysystemPluginCollection = [])
    {
        return $this->getFactory()
            ->createFlysystemFtpFileSystemBuilder($configTransfer, $flysystemPluginCollection)
            ->build();
    }
}
