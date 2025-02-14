<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Communication\Controller;

use Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Generated\Shared\Transfer\CustomerDataChangeResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CustomerDataChangeRequest\Business\CustomerDataChangeRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestRepositoryInterface getRepository()
 * @method \Spryker\Zed\CustomerDataChangeRequest\Communication\CustomerDataChangeRequestCommunicationFactory getFactory()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeResponseTransfer
     */
    public function changeCustomerDataAction(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): CustomerDataChangeResponseTransfer
    {
        return $this->getFacade()->changeCustomerData($customerDataChangeRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer $customerDataChangeRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer
     */
    public function getCustomerDataChangeRequestCollectionAction(
        CustomerDataChangeRequestCriteriaTransfer $customerDataChangeRequestCriteriaTransfer
    ): CustomerDataChangeRequestCollectionTransfer {
        return $this->getFacade()->getCustomerDataChangeRequestCollection($customerDataChangeRequestCriteriaTransfer);
    }
}
