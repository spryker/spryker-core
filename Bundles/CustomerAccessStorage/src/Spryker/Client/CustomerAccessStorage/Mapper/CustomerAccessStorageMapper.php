<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessStorage\Mapper;

use Generated\Shared\Transfer\CustomerAccessTransfer;

class CustomerAccessStorageMapper implements CustomerAccessStorageMapperInterface
{
    /**
     * @param array $unauthenticatedCustomerAccess
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function mapArrayToCustomerAccessTransfer(
        array $unauthenticatedCustomerAccess,
        CustomerAccessTransfer $customerAccessTransfer
    ): CustomerAccessTransfer {
        return $customerAccessTransfer->fromArray($unauthenticatedCustomerAccess, true);
    }
}
