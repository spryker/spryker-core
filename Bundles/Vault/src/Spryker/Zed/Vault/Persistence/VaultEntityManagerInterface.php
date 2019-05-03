<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Persistence;

use Generated\Shared\Transfer\VaultTransfer;

interface VaultEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\VaultTransfer $vaultTransfer
     *
     * @return bool
     */
    public function createVault(VaultTransfer $vaultTransfer): bool;
}
