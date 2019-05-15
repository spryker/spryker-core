<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\RestCartPermissionGroupsAttributesTransfer;

interface CartPermissionGroupMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     * @param \Generated\Shared\Transfer\RestCartPermissionGroupsAttributesTransfer $restCartPermissionGroupsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartPermissionGroupsAttributesTransfer
     */
    public function mapQuotePermissionGroupTransferToRestCartPermissionGroupsAttributesTransfer(
        QuotePermissionGroupTransfer $quotePermissionGroupTransfer,
        RestCartPermissionGroupsAttributesTransfer $restCartPermissionGroupsAttributesTransfer
    ): RestCartPermissionGroupsAttributesTransfer;
}
