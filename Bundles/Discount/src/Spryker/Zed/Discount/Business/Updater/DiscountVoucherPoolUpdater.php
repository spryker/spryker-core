<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Updater;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;

class DiscountVoucherPoolUpdater implements DiscountVoucherPoolUpdaterInterface
{
    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface
     */
    protected $discountRepository;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface
     */
    protected $discountEntityManager;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     * @param \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface $discountEntityManager
     */
    public function __construct(
        DiscountRepositoryInterface $discountRepository,
        DiscountEntityManagerInterface $discountEntityManager
    ) {
        $this->discountRepository = $discountRepository;
        $this->discountEntityManager = $discountEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return int|null
     */
    public function updateDiscountVoucherPool(DiscountConfiguratorTransfer $discountConfiguratorTransfer): ?int
    {
        $discountGeneralTransfer = $discountConfiguratorTransfer->getDiscountGeneralOrFail();

        if ($discountGeneralTransfer->getDiscountTypeOrFail() !== DiscountConstants::TYPE_VOUCHER) {
            return null;
        }

        if ($this->discountRepository->discountVoucherPoolExists($discountGeneralTransfer->getIdDiscountOrFail())) {
            return $this->discountEntityManager->updateDiscountVoucherPool($discountGeneralTransfer);
        }

        return $this->discountEntityManager->createDiscountVoucherPool($discountGeneralTransfer);
    }
}
