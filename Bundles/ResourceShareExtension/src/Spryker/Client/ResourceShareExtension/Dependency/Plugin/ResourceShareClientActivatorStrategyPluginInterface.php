<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShareExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;

interface ResourceShareClientActivatorStrategyPluginInterface
{
    /**
     * Specification:
     * - Executes additional actions, based on resource data and resource type values.
     * - Returns ResourceShareResponseTransfer with 'isSuccessful=true' and resource share details on success.
     * - Returns ResourceShareResponseTransfer with 'isSuccessful=false' and error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function execute(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer;

    /**
     * Specification:
     * - Checks if strategy plugin is applicable, based on resource data and provided customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareRequestTransfer $resourceShareRequestTransfer): bool;
}
