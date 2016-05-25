<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\Exception\PersistenceException;
use Spryker\Zed\Discount\Business\Voucher\VoucherEngine;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class DiscountPersist
{

    /**
     * @var \Spryker\Zed\Discount\Business\Voucher\VoucherEngine
     */
    protected $voucherEngine;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @param \Spryker\Zed\Discount\Business\Voucher\VoucherEngine $voucherEngine
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     */
    public function __construct(VoucherEngine $voucherEngine, DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->voucherEngine = $voucherEngine;
        $this->discountQueryContainer = $discountQueryContainer;
    }


    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return int
     */
    public function save(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $discountEntity = new SpyDiscount();
        $this->hydrateDiscountEntity($discountConfiguratorTransfer, $discountEntity);
        $discountEntity->save();

        if ($discountConfiguratorTransfer->getDiscountGeneral()->getDiscountType() === DiscountConstants::TYPE_VOUCHER) {
            $this->saveVoucherPool($discountEntity);
        }

        return $discountEntity->getIdDiscount();

    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\PersistenceException
     *
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function update(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $idDiscount = $discountConfiguratorTransfer
            ->requireDiscountGeneral()
            ->getDiscountGeneral()
            ->requireIdDiscount()
            ->getIdDiscount();

        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->findOneByIdDiscount($idDiscount);

        if (!$discountEntity) {
            throw new PersistenceException(
                sprintf(
                    'Discount with id "%d" not found in database.',
                    $idDiscount
                )
            );
        }

        $this->hydrateDiscountEntity($discountConfiguratorTransfer, $discountEntity);
        if ($discountConfiguratorTransfer->getDiscountGeneral()->getDiscountType() === DiscountConstants::TYPE_VOUCHER) {
            $this->saveVoucherPool($discountEntity);
        }

        $affectedRows = $discountEntity->save();

        return $affectedRows > 0;

    }

    /**
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\PersistenceException
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    public function saveVoucherCodes(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        $discountVoucherTransfer->requireIdDiscount();

        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->findOneByIdDiscount($discountVoucherTransfer->getIdDiscount());

        if (!$discountEntity) {
            throw new PersistenceException(
                sprintf(
                    'Discount with id "%d" not found in database.',
                    $discountVoucherTransfer->getIdDiscount()
                )
            );
        }

         return $this->persistVoucherCodes($discountVoucherTransfer, $discountEntity);
    }

    /**
     * @param int $idDiscount
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\PersistenceException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function toggleDiscountVisibility($idDiscount, $isActive = false)
    {
        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->findOneByIdDiscount($idDiscount);

        if (!$discountEntity) {
            throw new PersistenceException(
                sprintf(
                    'Discount with id "%d" not found in database.',
                    $idDiscount
                )
            );
        }

        $discountEntity->setIsActive($isActive);
        $affectedRows = $discountEntity->save();

        return $affectedRows > 0;
    }

    /**
     *
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
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
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
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
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
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
