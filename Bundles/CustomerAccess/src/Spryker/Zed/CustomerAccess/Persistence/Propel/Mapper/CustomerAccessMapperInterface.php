<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\SpyUnauthenticatedCustomerAccessEntityTransfer;

interface CustomerAccessMapperInterface
{
    /**
     * @param SpyUnauthenticatedCustomerAccessEntityTransfer $customerAccessEntity
     * @return ContentTypeAccessTransfer
     */
    public function mapEntityToTransfer(SpyUnauthenticatedCustomerAccessEntityTransfer $customerAccessEntity): ContentTypeAccessTransfer;
}