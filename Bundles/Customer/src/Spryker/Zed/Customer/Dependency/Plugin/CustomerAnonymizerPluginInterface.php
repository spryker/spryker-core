<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerAnonymizerPluginInterface
{
    /**
     * Specification:
     *  - process anonymization on related to customer subject
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function process(CustomerTransfer $customerTransfer);
}
