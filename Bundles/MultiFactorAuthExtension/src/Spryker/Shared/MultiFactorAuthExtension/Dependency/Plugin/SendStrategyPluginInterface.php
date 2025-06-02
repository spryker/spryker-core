<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;

/**
 * Use this plugin interface to implement a multi-factor authentication send strategy.
 * - This plugin is used to send the multi-factor authentication code to the customer.
 * - The plugin should be registered in the MultiFactorAuthDependencyProvider::getMultiFactorAuthSendStrategyPlugins() method.
 */
interface SendStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if the strategy is applicable for the given MultiFactorAuthTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return bool
     */
    public function isApplicable(MultiFactorAuthTransfer $multiFactorAuthTransfer): bool;

    /**
     * Specification:
     * - Sends the multi-factor authentication code.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function send(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer;
}
