<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Plugin runs when customer impersonation is finished.
 * Implement it when you need to execute a cleanup/perform action after customer impersonation is finished.
 */
interface ImpersonationFinisherPluginInterface
{
    /**
     * Specification:
     * - Finishes process related for logged customer at the end of customer impersonation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function finish(CustomerTransfer $customerTransfer): void;
}
