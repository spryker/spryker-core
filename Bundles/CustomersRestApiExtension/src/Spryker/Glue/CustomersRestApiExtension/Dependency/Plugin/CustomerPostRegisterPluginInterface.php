<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

/**
 * @deprecated use \Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerPostCreatePluginInterface
 */
interface CustomerPostRegisterPluginInterface
{
    /**
     * Specification:
     *  - Executes after customer registers via REST API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function postRegister(CustomerTransfer $customerTransfer): CustomerTransfer;
}
