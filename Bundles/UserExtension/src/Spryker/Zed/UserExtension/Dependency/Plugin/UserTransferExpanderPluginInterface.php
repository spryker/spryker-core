<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserExtension\Dependency\Plugin;

use Generated\Shared\Transfer\UserTransfer;

interface UserTransferExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands user transfer with required data during fetching user data from storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function expandUserTransfer(UserTransfer $userTransfer): UserTransfer;
}
