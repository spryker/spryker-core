<?php

namespace Spryker\Zed\BusinessOnBehalf\Communication\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;

class CustomerDefaultCompanyUserExpanderPlugin implements CustomerTransferExpanderPluginInterface
{
    /**
     * Specification
     * - Expands the provided customer transfer object's data and returns the modified object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandTransfer(CustomerTransfer $customerTransfer)
    {
        if ($customerTransfer->getCompanyUserTransfer()) {
            return $customerTransfer;
        }

        return $this->getFacade()->setDefaultCompanyUserToCustomer($customerTransfer);
    }
}
