<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Dependency\Plugin;

use Generated\Shared\Transfer\FlysystemConfigTransfer;

interface FlysystemFilesystemBuilderPluginInterface
{
    /**
     * Specification:
     *  - Create a Filesystem with parameters from FlysystemConfigTransfer
     *  - Pass thephpleague/flysystem related plugins under $flysystemPluginCollection
     *  - Return class implementing of \League\Flysystem\FilesystemInterface
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     * @param \League\Flysystem\PluginInterface[] $flysystemPluginCollection
     *
     * @return \League\Flysystem\FilesystemInterface
     */
    public function build(FlysystemConfigTransfer $configTransfer, array $flysystemPluginCollection = []);

    /**
     * Specification:
     *  - Returns true if builder type is accepted
     *
     * @api
     *
     * @param string $type
     *
     * @return bool
     */
    public function acceptType($type);
}
