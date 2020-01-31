<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\DiscountPromotion\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\DiscountPromotionBuilder;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class DiscountPromotionDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function haveDiscountPromotion(array $override = []): DiscountPromotionTransfer
    {
        $discountPromotionTransfer = (new DiscountPromotionBuilder($override))->build();

        $this->debugSection('DiscountPromotion', $discountPromotionTransfer->toArray());
        $discountPromotionTransfer = $this->getDiscountPromotionFacade()
            ->createPromotionDiscount($discountPromotionTransfer);
        $this->debugSection('DiscountPromotion Id', $discountPromotionTransfer->getIdDiscountPromotion());

        $cleanupModule = $this->getDataCleanupHelper();
        $cleanupModule->_addCleanup(function () use ($discountPromotionTransfer): void {
            $this->debug('Deleting DiscountPromotion: ' . $discountPromotionTransfer->getIdDiscountPromotion());
            $this->getDiscountPromotionQuery()
                ->queryDiscountPromotionByIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion())
                ->delete();
        });

        return $discountPromotionTransfer;
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface
     */
    private function getDiscountPromotionFacade(): DiscountPromotionFacadeInterface
    {
        return $this->getLocator()
            ->discountPromotion()
            ->facade();
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface
     */
    private function getDiscountPromotionQuery(): DiscountPromotionQueryContainerInterface
    {
        return $this->getLocator()
            ->discountPromotion()
            ->queryContainer();
    }
}
