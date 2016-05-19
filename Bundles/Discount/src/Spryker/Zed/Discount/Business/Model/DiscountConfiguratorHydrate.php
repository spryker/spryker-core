<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConditionTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class DiscountConfiguratorHydrate
{
    /**
     * @var DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * DiscountConfiguratorHydrate constructor.
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @param int $idDiscount
     *
     * @return DiscountConfiguratorTransfer
     */
    public function getByIdDiscount($idDiscount)
    {
        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->findOneByIdDiscount($idDiscount);

        $discountConfigurator = $this->createDiscountConfiguratorTransfer();

        $discountGeneralTransfer = $this->hydrateGeneralDiscount($discountEntity);
        $discountConfigurator->setDiscountGeneral($discountGeneralTransfer);

        $discountCalculatorTransfer = $this->hydrateDiscountCalculator($discountEntity);
        $discountConfigurator->setDiscountCalculator($discountCalculatorTransfer);

        $discountConditionTransfer = $this->hydrateDiscountCondition($discountEntity);
        $discountConfigurator->setDiscountCondition($discountConditionTransfer);

        $this->hydrateDiscountVoucher($idDiscount, $discountEntity, $discountConfigurator);

        return $discountConfigurator;

    }

    /**
     * @param SpyDiscount $discountEntity
     *
     * @return DiscountGeneralTransfer
     */
    protected function hydrateGeneralDiscount(SpyDiscount $discountEntity)
    {
        $discountGeneralTransfer = new DiscountGeneralTransfer();
        $discountGeneralTransfer->fromArray($discountEntity->toArray(), true);

        $voucherType = 'cart_rule';
        if ($discountEntity->getFkDiscountVoucherPool()) {
            $voucherType = 'voucher';
        }
        $discountGeneralTransfer->setDiscountType($voucherType);

        $discountGeneralTransfer->setValidFrom($discountEntity->getValidFrom());
        $discountGeneralTransfer->setValidTo($discountEntity->getValidTo());
        return $discountGeneralTransfer;
    }

    /**
     * @param SpyDiscount $discountEntity
     *
     * @return DiscountCalculatorTransfer
     */
    protected function hydrateDiscountCalculator(SpyDiscount $discountEntity)
    {
        $discountCalculatorTransfer = new DiscountCalculatorTransfer();
        $discountCalculatorTransfer->fromArray($discountEntity->toArray(), true);
        return $discountCalculatorTransfer;
    }

    /**
     * @param SpyDiscount $discountEntity
     *
     * @return DiscountConditionTransfer
     */
    protected function hydrateDiscountCondition(SpyDiscount $discountEntity)
    {
        $discountConditionTransfer = new DiscountConditionTransfer();
        $discountConditionTransfer->fromArray($discountEntity->toArray(), true);
        return $discountConditionTransfer;
    }

    /**
     * @param $idDiscount
     * @param SpyDiscount $discountEntity
     * @param DiscountConfiguratorTransfer $discountConfigurator
     *
     * @return void
     */
    protected function hydrateDiscountVoucher(
        $idDiscount,
        SpyDiscount $discountEntity,
        DiscountConfiguratorTransfer $discountConfigurator
    ) {
        $voucherPoolEntity = $discountEntity->getVoucherPool();
        if ($voucherPoolEntity) {
            $discountVoucherTransfer = new DiscountVoucherTransfer();
            $discountVoucherTransfer->setIdDiscount($idDiscount);
            $discountVoucherTransfer->setFkDiscountVoucherPool($discountEntity->getFkDiscountVoucherPool());
            $discountConfigurator->setDiscountVoucher($discountVoucherTransfer);
        }
    }

    /**
     * @return DiscountConfiguratorTransfer
     */
    protected function createDiscountConfiguratorTransfer()
    {
        return new DiscountConfiguratorTransfer();
    }
}
