<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Persistence\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SpyCustomerEntityTransfer;

interface CustomerMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCustomerEntityTransfer $customerEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapCustomerEntityToCustomer(SpyCustomerEntityTransfer $customerEntityTransfer): CustomerTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCustomerEntityTransfer
     */
    public function mapCustomerToCustomerEntity(CustomerTransfer $customerTransfer): SpyCustomerEntityTransfer;
}
