<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\ButtonCreator;

use Generated\Shared\Transfer\ButtonTransfer;

interface MerchantProfileChangeStatusButtonCreatorInterface
{
    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer|null
     */
    public function getChangeStatusButton(int $idMerchant): ?ButtonTransfer;
}
