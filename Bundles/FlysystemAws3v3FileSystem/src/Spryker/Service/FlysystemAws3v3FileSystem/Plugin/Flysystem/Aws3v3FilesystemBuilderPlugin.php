<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemAws3v3FileSystem\Plugin\Flysystem;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Spryker\Service\Flysystem\Dependency\Plugin\FlysystemFilesystemBuilderPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Service\FlysystemAws3v3FileSystem\FlysystemAws3v3FileSystemServiceFactory getFactory()
 */
class Aws3v3FilesystemBuilderPlugin extends AbstractPlugin implements FlysystemFilesystemBuilderPluginInterface
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
            ->createFlysystemAws3v3FileSystemBuilder($configTransfer, $flysystemPluginCollection)
            ->build();
    }
}
