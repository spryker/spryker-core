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
     * @api
     *
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     * @param \League\Flysystem\PluginInterface[] $flysystemPluginCollection
     *
     * @return \League\Flysystem\Filesystem
     */
    public function build(FlysystemConfigTransfer $configTransfer, array $flysystemPluginCollection = []);

    /**
     * @api
     *
     * @param string $type
     *
     * @return bool
     */
    public function acceptType($type);

}
