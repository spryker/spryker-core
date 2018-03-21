<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\SpyPermissionEntityTransfer;
use Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer;

interface SharedCartEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyPermissionEntityTransfer $permissionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPermissionEntityTransfer
     */
    public function savePermissionEntity(SpyPermissionEntityTransfer $permissionEntityTransfer): SpyPermissionEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer
     */
    public function saveQuotePermissionGroupEntity(SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer): SpyQuotePermissionGroupEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer
     * @param \Generated\Shared\Transfer\SpyPermissionEntityTransfer $permissionEntityTransfer
     *
     * @return void
     */
    public function saveQuotePermissionGroupToPermissionEntity(
        SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer,
        SpyPermissionEntityTransfer $permissionEntityTransfer
    ): void;
}
