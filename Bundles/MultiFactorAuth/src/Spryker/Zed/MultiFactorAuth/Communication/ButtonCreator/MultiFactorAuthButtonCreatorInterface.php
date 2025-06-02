<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\ButtonCreator;

interface MultiFactorAuthButtonCreatorInterface
{
    /**
     * @param int $idCustomer
     * @param array<\Generated\Shared\Transfer\ButtonTransfer> $buttonTransfers
     *
     * @return array<\Generated\Shared\Transfer\ButtonTransfer>
     */
    public function addRemoveMultiFactorAuthButton(int $idCustomer, array $buttonTransfers): array;
}
