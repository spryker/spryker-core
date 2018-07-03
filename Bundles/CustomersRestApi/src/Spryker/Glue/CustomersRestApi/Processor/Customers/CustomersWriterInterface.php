<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customers;

use Generated\Shared\Transfer\RestRegisterCustomerAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CustomersWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestRegisterCustomerAttributesTransfer $restRegisterCustomerAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function registerCustomer(RestRegisterCustomerAttributesTransfer $restRegisterCustomerAttributesTransfer): RestResponseInterface;
}
