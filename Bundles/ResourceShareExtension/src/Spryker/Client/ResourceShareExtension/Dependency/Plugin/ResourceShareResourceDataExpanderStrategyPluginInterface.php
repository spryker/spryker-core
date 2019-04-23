<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShareExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

interface ResourceShareResourceDataExpanderStrategyPluginInterface
{
    /**
     * Specification:
     * - Expands resource data field with additional data, based on resource type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function expand(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer;

    /**
     * Specification:
     * - Checks if strategy plugin is applicable, based on resource type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareTransfer $resourceShareTransfer): bool;
}
