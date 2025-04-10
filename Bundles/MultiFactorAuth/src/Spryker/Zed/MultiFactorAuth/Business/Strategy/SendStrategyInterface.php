<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Strategy;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;

interface SendStrategyInterface
{
    /**
     * Specification:
     * - Checks if the strategy is applicable for the given MultiFactorAuthTransfer.
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
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function send(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer;
}
