<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Persistence;

use Generated\Shared\Transfer\VaultDepositTransfer;

interface VaultEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\VaultDepositTransfer $vaultDepositTransfer
     *
     * @return bool
     */
    public function createVaultDeposit(VaultDepositTransfer $vaultDepositTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\VaultDepositTransfer $vaultDepositTransfer
     *
     * @return void
     */
    public function updateVaultDeposit(VaultDepositTransfer $vaultDepositTransfer): void;
}
