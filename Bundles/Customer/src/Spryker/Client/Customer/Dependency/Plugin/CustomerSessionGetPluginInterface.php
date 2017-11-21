<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
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
     * @param CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function executes(CustomerTransfer $customerTransfer);
}
