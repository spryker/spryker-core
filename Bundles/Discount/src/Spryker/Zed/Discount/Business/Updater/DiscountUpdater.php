<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Updater;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface;
use Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DiscountUpdater implements DiscountUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface
     */
    protected $discountMapper;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface
     */
    protected $discountEntityManager;

    /**
     * @var \Spryker\Zed\Discount\Business\Updater\DiscountVoucherPoolUpdaterInterface
     */
    protected $discountVoucherPoolUpdater;

    /**
     * @param \Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface $discountMapper
     * @param \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface $discountEntityManager
     * @param \Spryker\Zed\Discount\Business\Updater\DiscountVoucherPoolUpdaterInterface $discountVoucherPoolUpdater
     */
    public function __construct(
        DiscountMapperInterface $discountMapper,
        DiscountEntityManagerInterface $discountEntityManager,
        DiscountVoucherPoolUpdaterInterface $discountVoucherPoolUpdater
    ) {
        $this->discountMapper = $discountMapper;
        $this->discountEntityManager = $discountEntityManager;
        $this->discountVoucherPoolUpdater = $discountVoucherPoolUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return void
     */
    public function updateDiscount(DiscountConfiguratorTransfer $discountConfiguratorTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($discountConfiguratorTransfer) {
            $this->executeUpdateDiscountTransaction($discountConfiguratorTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return void
     */
    protected function executeUpdateDiscountTransaction(DiscountConfiguratorTransfer $discountConfiguratorTransfer): void
    {
        $idDiscountVoucherPool = $this->discountVoucherPoolUpdater->updateDiscountVoucherPool($discountConfiguratorTransfer);

        $discountTransfer = $this->discountMapper->mapDiscountConfiguratorTransferToDiscountTransfer(
            $discountConfiguratorTransfer,
            new DiscountTransfer(),
        );
        $discountTransfer->setFkDiscountVoucherPool($idDiscountVoucherPool);
        $this->discountEntityManager->updateDiscount($discountTransfer);
    }
}
