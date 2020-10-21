<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerImpersonationSanitizerPluginInterface
{
    /**
     * Specification:
     * - Sanitizes data related to the end of customer impersonation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function sanitize(CustomerTransfer $customerTransfer): void;
}
