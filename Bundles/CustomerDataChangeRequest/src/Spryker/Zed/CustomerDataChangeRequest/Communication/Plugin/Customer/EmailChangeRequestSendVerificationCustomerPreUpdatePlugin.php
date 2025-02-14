<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Communication\Plugin\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestTypeEnum;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPreUpdatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerDataChangeRequest\Business\CustomerDataChangeRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig getConfig()
 * @method \Spryker\Zed\CustomerDataChangeRequest\Communication\CustomerDataChangeRequestCommunicationFactory getFactory()
 */
class EmailChangeRequestSendVerificationCustomerPreUpdatePlugin extends AbstractPlugin implements CustomerPreUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function preUpdate(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if (
            $customerTransfer->getChangeRequest() !== null &&
            $customerTransfer->getChangeRequest()->getTypeOrFail() === CustomerDataChangeRequestTypeEnum::EMAIL->value
        ) {
            return $customerTransfer;
        }

        $sendVerificationTokenCustomerChangeDataResponseTransfer = $this->getFacade()->sendVerificationEmail($customerTransfer);

        if ($sendVerificationTokenCustomerChangeDataResponseTransfer->getIsSent()) {
            $customerTransfer->setMessage('customer.change_customer_email_mail_sent');
        }

        return $customerTransfer;
    }
}
