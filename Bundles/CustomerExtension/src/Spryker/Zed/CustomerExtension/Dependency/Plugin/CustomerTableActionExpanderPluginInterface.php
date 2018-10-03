<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ButtonTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerTableActionExpanderPluginInterface
{
    /**
     * Specification:
     *  - This plugin allows to execute additional actions for customer table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    public function prepareButton(CustomerTransfer $customerTransfer): ButtonTransfer;
}
