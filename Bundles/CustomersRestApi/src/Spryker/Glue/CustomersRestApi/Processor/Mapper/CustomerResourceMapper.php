<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer;

class CustomerResourceMapper implements CustomerResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCustomersAttributesTransfer $restCustomersAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapCustomerAttributesToCustomerTransfer(RestCustomersAttributesTransfer $restCustomersAttributesTransfer): CustomerTransfer
    {
        return (new CustomerTransfer())->fromArray($restCustomersAttributesTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer
     */
    public function mapCustomerTransferToRestCustomersResponseAttributesTransfer(CustomerTransfer $customerTransfer): RestCustomersResponseAttributesTransfer
    {
        return (new RestCustomersResponseAttributesTransfer())->fromArray($customerTransfer->toArray(), true);
    }
}
