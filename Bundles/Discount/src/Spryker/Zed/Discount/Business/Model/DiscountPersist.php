<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Zed\Discount\Business\Voucher\VoucherEngine;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class DiscountPersist
{
    /**
     * @var VoucherEngine
     */
    protected $voucherEngine;

    /**
     * @var DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @param VoucherEngine $voucherEngine
     * @param DiscountQueryContainerInterface $discountQueryContainer
     */
    public function __construct(VoucherEngine $voucherEngine, DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->voucherEngine = $voucherEngine;
        $this->discountQueryContainer = $discountQueryContainer;
    }


    /**
     * @param DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return int
     */
    public function save(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $discountEntity = new SpyDiscount();
        $this->hydrateDiscountEntity($discountConfiguratorTransfer, $discountEntity);
        $discountEntity->save();

        if ($discountConfiguratorTransfer->getDiscountGeneral()->getDiscountType() == 'voucher') {
            $this->saveVoucherPool($discountEntity);
        }

        return $discountEntity->getIdDiscount();

    }

    /**
     * @param DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return bool
     */
    public function update(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $idDiscount = $discountConfiguratorTransfer->getDiscountGeneral()
            ->getIdDiscount();

        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->findOneByIdDiscount($idDiscount);

        if (!$discountEntity) {
            return false;
        }

        $this->hydrateDiscountEntity($discountConfiguratorTransfer, $discountEntity);

        if ($discountConfiguratorTransfer->getDiscountGeneral()->getDiscountType() == 'voucher') {
            $this->saveVoucherPool($discountEntity);
        }

        $affectedRows = $discountEntity->save();

        return $affectedRows > 0;

    }

    /**
     * @param DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    public function saveVoucherCodes(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->findOneByIdDiscount($discountVoucherTransfer->getIdDiscount());

         return $this->persistVoucherCodes($discountVoucherTransfer, $discountEntity);
    }

    /**
     * @param int $idDiscount
     * @param bool $isActive
     *
     * @return bool
     */
    public function toggleDiscountVisibility($idDiscount, $isActive = false)
    {
        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->findOneByIdDiscount($idDiscount);

        $discountEntity->setIsActive($isActive);
        $affectedRows = $discountEntity->save();

        return $affectedRows > 0;
    }

    /**
     *
     * @param SpyDiscount $discountEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return SpyDiscountVoucherPool
     */
    protected function saveVoucherPool(SpyDiscount $discountEntity)
    {
        if ($discountEntity->getFkDiscountVoucherPool()) {
            return $discountEntity->getVoucherPool();
        }

        $discountVoucherPoolEntity = new SpyDiscountVoucherPool();
        $discountVoucherPoolEntity->setName($discountEntity->getDisplayName());
        $discountVoucherPoolEntity->setIsActive(true);
        $discountVoucherPoolEntity->save();

        $discountEntity->setVoucherPool($discountVoucherPoolEntity);
        $discountEntity->save();

        return $discountVoucherPoolEntity;
    }

    /**
     * @param DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param SpyDiscount $discountEntity
     *
     * @return void
     *
     */
    protected function hydrateDiscountEntity(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer,
        SpyDiscount $discountEntity
    ) {
        $discountEntity->fromArray($discountConfiguratorTransfer->getDiscountGeneral()->toArray());
        $discountEntity->setAmount($discountConfiguratorTransfer->getDiscountCalculator()->getAmount());
        $discountEntity->setCalculatorPlugin($discountConfiguratorTransfer->getDiscountCalculator()->getCalculatorPlugin());
        $discountEntity->setCollectorQueryString($discountConfiguratorTransfer->getDiscountCalculator()->getCollectorQueryString());
        $discountEntity->setDecisionRuleQueryString($discountConfiguratorTransfer->getDiscountCondition()->getDecisionRuleQueryString());
    }

    /**
     * @param DiscountVoucherTransfer $discountVoucherTransfer
     * @param SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    protected function persistVoucherCodes(
        DiscountVoucherTransfer $discountVoucherTransfer,
        SpyDiscount $discountEntity
    ) {

        $discountVoucherPoolEntity = $this->saveVoucherPool($discountEntity);

        $discountEntity->setFkDiscountVoucherPool($discountVoucherPoolEntity->getIdDiscountVoucherPool());
        $discountVoucherTransfer->setFkDiscountVoucherPool($discountVoucherPoolEntity->getIdDiscountVoucherPool());

        return $this->voucherEngine->createVoucherCodes($discountVoucherTransfer);
    }
}
