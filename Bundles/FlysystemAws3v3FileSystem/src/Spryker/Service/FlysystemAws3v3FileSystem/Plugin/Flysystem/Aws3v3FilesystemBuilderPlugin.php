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
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $type
     *
     * @return bool
     */
    public function acceptType($type)
    {
        return $type === static::class;
    }

    /**
     * {@inheritDoc}
     * - Returns a Filesystem to work with AWS S3.
     * - Requires a `FlysystemConfig.adapterConfig` to be set and to have next keys: `key`, `secret`, `region`, `bucket`, `path`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     *
     * @return \League\Flysystem\Filesystem
     */
    public function build(FlysystemConfigTransfer $configTransfer)
    {
        return $this->getFactory()
            ->createFlysystemAws3v3FileSystemBuilder($configTransfer)
            ->build();
    }
}
