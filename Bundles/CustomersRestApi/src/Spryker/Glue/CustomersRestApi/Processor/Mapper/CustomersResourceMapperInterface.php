<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestRegisterCustomerAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface CustomersResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestRegisterCustomerAttributesTransfer $restRegisterCustomerAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapCustomerAttributesToCustomerTransfer(RestRegisterCustomerAttributesTransfer $restRegisterCustomerAttributesTransfer): CustomerTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapCustomerToCustomersRestResource(CustomerTransfer $customerTransfer): RestResourceInterface;
}
