<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model\Status;

interface MerchantStatusValidatorInterface
{
    /**
     * @param int $idMerchant
     * @param string $newStatus
     *
     * @return bool
     */
    public function isMerchantStatusTransitionValid(int $idMerchant, string $newStatus): bool;
}
