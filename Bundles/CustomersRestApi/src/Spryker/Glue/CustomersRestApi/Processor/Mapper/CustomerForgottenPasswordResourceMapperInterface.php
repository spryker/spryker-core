<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomerForgottenPasswordAttributesTransfer;

interface CustomerForgottenPasswordResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCustomerForgottenPasswordAttributesTransfer $restCustomerForgottenPasswordAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapCustomerForgottenPasswordAttributesToCustomerTransfer(RestCustomerForgottenPasswordAttributesTransfer $restCustomerForgottenPasswordAttributesTransfer): CustomerTransfer;
}
