<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Table\CustomerTableActionExpanderPluginExecutor;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerTableActionExpanderPluginExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function execute(CustomerTransfer $customerTransfer): array;
}
