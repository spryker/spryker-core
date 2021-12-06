<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Creator;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface;

class DiscountVoucherPoolCreator implements DiscountVoucherPoolCreatorInterface
{
    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface
     */
    protected $discountEntityManager;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface $discountEntityManager
     */
    public function __construct(DiscountEntityManagerInterface $discountEntityManager)
    {
        $this->discountEntityManager = $discountEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return int|null
     */
    public function createDiscountVoucherPool(DiscountConfiguratorTransfer $discountConfiguratorTransfer): ?int
    {
        $discountGeneralTransfer = $discountConfiguratorTransfer->getDiscountGeneralOrFail();
        if ($discountGeneralTransfer->getDiscountTypeOrFail() !== DiscountConstants::TYPE_VOUCHER) {
            return null;
        }

        return $this->discountEntityManager->createDiscountVoucherPool($discountGeneralTransfer);
    }
}
