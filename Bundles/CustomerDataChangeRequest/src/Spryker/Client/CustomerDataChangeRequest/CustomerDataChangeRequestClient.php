<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerDataChangeRequest;

use Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Generated\Shared\Transfer\CustomerDataChangeResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CustomerDataChangeRequest\CustomerDataChangeRequestFactory getFactory()
 */
class CustomerDataChangeRequestClient extends AbstractClient implements CustomerDataChangeRequestClientInterface
{
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
        return $this->getFactory()
            ->createZedCustomerDataChangeRequestStub()
            ->changeCustomerData($customerDataChangeRequestTransfer);
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
        return $this->getFactory()
            ->createZedCustomerDataChangeRequestStub()
            ->getCustomerDataChangeRequestCollection($customerDataChangeRequestCriteriaTransfer);
    }
}
