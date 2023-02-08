<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserExtension\Dependency\Plugin;

use Generated\Shared\Transfer\UserCollectionTransfer;

/**
 * Implement this plugin if you want to expand a collection of `UserTransfer` with additional data.
 */
interface UserExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands user transfers with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function expand(UserCollectionTransfer $userCollectionTransfer): UserCollectionTransfer;
}
