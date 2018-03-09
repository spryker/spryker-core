<?php

namespace Spryker\Zed\CustomerAccess\Business\Model;

use Generated\Shared\Transfer\CustomerAccessTransfer;

interface CustomerAccessUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return void
     */
    public function updateOnlyContentTypesToAccessible(CustomerAccessTransfer $customerAccessTransfer);
}