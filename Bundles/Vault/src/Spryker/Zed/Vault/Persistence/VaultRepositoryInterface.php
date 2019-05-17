<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Persistence;

use Generated\Shared\Transfer\VaultDepositTransfer;

interface VaultRepositoryInterface
{
    /**
     * @param string $dataType
     * @param string $dataKey
     *
     * @return \Generated\Shared\Transfer\VaultDepositTransfer|null
     */
    public function findVaultDepositByDataTypeAndKey(string $dataType, string $dataKey): ?VaultDepositTransfer;
}
