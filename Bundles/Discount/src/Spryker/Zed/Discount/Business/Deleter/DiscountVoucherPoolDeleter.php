<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Deleter;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface;

class DiscountVoucherPoolDeleter implements DiscountVoucherPoolDeleterInterface
{
    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface $discountEntityManager
     */
    public function __construct(protected DiscountEntityManagerInterface $discountEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function deleteDiscountVoucherPoolByDiscountConfigurator(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer
    ): DiscountConfiguratorTransfer {
        if ($discountConfiguratorTransfer->getDiscountGeneralOrFail()->getDiscountTypeOrFail() === DiscountConstants::TYPE_VOUCHER) {
            return $discountConfiguratorTransfer;
        }

        $idDiscountVoucherPool = $discountConfiguratorTransfer->getDiscountVoucher()?->getFkDiscountVoucherPool();

        if (!$idDiscountVoucherPool) {
            return $discountConfiguratorTransfer;
        }

        $this->discountEntityManager->deleteDiscountVouchersByIdDiscountVoucherPool($idDiscountVoucherPool);
        $this->discountEntityManager->deleteDiscountVoucherPoolByIdDiscountVoucherPool($idDiscountVoucherPool);

        return $discountConfiguratorTransfer;
    }
}
