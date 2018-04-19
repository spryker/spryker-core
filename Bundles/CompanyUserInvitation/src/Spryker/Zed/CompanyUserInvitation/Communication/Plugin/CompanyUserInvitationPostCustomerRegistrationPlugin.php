<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Communication\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\PostCustomerRegistrationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Communication\CompanyUserInvitationCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface getFacade()
 */
class CompanyUserInvitationPostCustomerRegistrationPlugin extends AbstractPlugin implements PostCustomerRegistrationPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer): void
    {
        $this->getFactory()->createCompanyUserCreator()->create($customerTransfer);
    }
}
