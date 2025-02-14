<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business;

use Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Generated\Shared\Transfer\CustomerDataChangeResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\VerificationTokenCustomerChangeDataResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerDataChangeRequest\Business\CustomerDataChangeRequestBusinessFactory getFactory()
 * @method \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestRepositoryInterface getRepository()
 */
class CustomerDataChangeRequestFacade extends AbstractFacade implements CustomerDataChangeRequestFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\VerificationTokenCustomerChangeDataResponseTransfer
     */
    public function sendVerificationEmail(CustomerTransfer $customerTransfer): VerificationTokenCustomerChangeDataResponseTransfer
    {
        return $this->getFactory()->createVerificationEmailSender()->send($customerTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeResponseTransfer
     */
    public function changeCustomerData(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): CustomerDataChangeResponseTransfer
    {
        return $this->getFactory()->createCustomerDataChangeRequestWriter()->changeCustomerData($customerDataChangeRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer $customerDataChangeRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer
     */
    public function getCustomerDataChangeRequestCollection(
        CustomerDataChangeRequestCriteriaTransfer $customerDataChangeRequestCriteriaTransfer
    ): CustomerDataChangeRequestCollectionTransfer {
        return $this->getRepository()->get($customerDataChangeRequestCriteriaTransfer);
    }
}
