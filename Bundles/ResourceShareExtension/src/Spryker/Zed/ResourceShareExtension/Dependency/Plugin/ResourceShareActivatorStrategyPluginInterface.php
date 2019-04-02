<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShareExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ResourceShareTransfer;

interface ResourceShareActivatorStrategyPluginInterface
{
    /**
     * Specification:
     * - Executes additional actions, based on resource data and resource type values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    public function execute(ResourceShareTransfer $resourceShareTransfer): ResourceShareTransfer;
}
