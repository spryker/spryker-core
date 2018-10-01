<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Discount\Business\Persistence;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;

interface DiscountConfiguratorHydrateInterface
{
    /**
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer|null
     */
    public function getByIdDiscount($idDiscount): ?DiscountConfiguratorTransfer;
}
