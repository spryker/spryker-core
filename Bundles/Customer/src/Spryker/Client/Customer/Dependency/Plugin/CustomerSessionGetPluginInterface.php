<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerSessionGetPluginInterface
{
    /**
     * Specification
     *  - executes custom operation after it reads
     *    the customer from session
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer);
}
