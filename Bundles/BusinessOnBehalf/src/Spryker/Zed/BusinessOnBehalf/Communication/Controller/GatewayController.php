<?php

namespace Spryker\Zed\BusinessOnBehalf\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\BusinessOnBehalf\Business\BusinessOnBehalfFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findActiveCompanyUsersByCustomerIdAction(CustomerTransfer $customerTransfer): array
    {
        return $this->getFacade()->findActiveCompanyUsersByCustomerId( $customerTransfer);
    }
}