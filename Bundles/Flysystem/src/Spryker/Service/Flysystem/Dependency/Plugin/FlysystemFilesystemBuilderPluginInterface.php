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
     *  - Return class implementing of \League\Flysystem\FilesystemOperator
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     *
     * @return \League\Flysystem\FilesystemOperator
     */
    public function build(FlysystemConfigTransfer $configTransfer);

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
