<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\PostUpdater;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountPromotionCreatorInterface;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionUpdater\DiscountPromotionUpdaterInterface;

class DiscountPromotionDiscountPostUpdater implements DiscountPromotionDiscountPostUpdaterInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountPromotionCreatorInterface
     */
    protected DiscountPromotionCreatorInterface $discountPromotionCreator;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionUpdater\DiscountPromotionUpdaterInterface
     */
    protected DiscountPromotionUpdaterInterface $discountPromotionUpdater;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountPromotionCreatorInterface $discountPromotionCreator
     * @param \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionUpdater\DiscountPromotionUpdaterInterface $discountPromotionUpdater
     */
    public function __construct(
        DiscountPromotionCreatorInterface $discountPromotionCreator,
        DiscountPromotionUpdaterInterface $discountPromotionUpdater
    ) {
        $this->discountPromotionCreator = $discountPromotionCreator;
        $this->discountPromotionUpdater = $discountPromotionUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function postUpdate(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorTransfer
    {
        $discountCalculatorTransfer = $discountConfiguratorTransfer->getDiscountCalculatorOrFail();

        $discountPromotionTransfer = $discountCalculatorTransfer->getDiscountPromotion();
        if (!$discountPromotionTransfer) {
            $discountCalculatorTransfer->setDiscountPromotion(new DiscountPromotionTransfer());

            return $discountConfiguratorTransfer;
        }

        $discountPromotionTransfer->setFkDiscount(
            $discountConfiguratorTransfer->getDiscountGeneralOrFail()->getIdDiscountOrFail(),
        );

        $discountPromotionTransfer = $this->saveDiscountPromotion($discountPromotionTransfer);
        $discountCalculatorTransfer->setDiscountPromotion($discountPromotionTransfer);

        return $discountConfiguratorTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    protected function saveDiscountPromotion(DiscountPromotionTransfer $discountPromotionTransfer): DiscountPromotionTransfer
    {
        if (!$discountPromotionTransfer->getIdDiscountPromotion()) {
            return $this->discountPromotionCreator->create($discountPromotionTransfer);
        }

        return $this->discountPromotionUpdater->update($discountPromotionTransfer);
    }
}
