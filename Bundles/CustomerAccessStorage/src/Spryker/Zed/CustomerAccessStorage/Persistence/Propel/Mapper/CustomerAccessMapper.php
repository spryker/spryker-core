<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\SpyUnauthenticatedCustomerAccessEntityTransfer;

class CustomerAccessMapper implements CustomerAccessMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyUnauthenticatedCustomerAccessEntityTransfer $customerAccessEntity
     *
     * @return \Generated\Shared\Transfer\ContentTypeAccessTransfer
     */
    public function mapEntityToTransfer(SpyUnauthenticatedCustomerAccessEntityTransfer $customerAccessEntity): ContentTypeAccessTransfer
    {
        return (new ContentTypeAccessTransfer())->fromArray($customerAccessEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeAccessTransfer $customerAccessEntity
     *
     * @return \Generated\Shared\Transfer\SpyUnauthenticatedCustomerAccessEntityTransfer
     */
    public function mapTransferToEntity(ContentTypeAccessTransfer $customerAccessEntity): SpyUnauthenticatedCustomerAccessEntityTransfer
    {
        return (new SpyUnauthenticatedCustomerAccessEntityTransfer())->fromArray($customerAccessEntity->toArray());
    }
}
