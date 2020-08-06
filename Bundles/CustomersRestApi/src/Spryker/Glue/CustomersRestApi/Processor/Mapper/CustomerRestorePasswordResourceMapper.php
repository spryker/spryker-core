<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomerRestorePasswordAttributesTransfer;

class CustomerRestorePasswordResourceMapper implements CustomerRestorePasswordResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCustomerRestorePasswordAttributesTransfer $restCustomerRestorePasswordAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapCustomerRestorePasswordAttributesToCustomerTransfer(
        RestCustomerRestorePasswordAttributesTransfer $restCustomerRestorePasswordAttributesTransfer
    ): CustomerTransfer {
        return (new CustomerTransfer())->fromArray($restCustomerRestorePasswordAttributesTransfer->toArray(), true);
    }
}
