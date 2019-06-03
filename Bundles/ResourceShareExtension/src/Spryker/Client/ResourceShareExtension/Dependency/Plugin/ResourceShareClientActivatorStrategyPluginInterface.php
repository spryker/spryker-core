<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShareExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface;

interface ResourceShareClientActivatorStrategyPluginInterface extends ResourceShareActivatorStrategyPluginInterface
{
    /**
     * Specification:
     * - Determines if strategy plugin requires the customer to be logged in.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    public function isLoginRequired(?CustomerTransfer $customerTransfer): bool;
}
