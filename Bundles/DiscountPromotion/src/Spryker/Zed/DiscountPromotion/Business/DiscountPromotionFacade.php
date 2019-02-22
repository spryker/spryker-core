<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionBusinessFactory getFactory()
 */
class DiscountPromotionFacade extends AbstractFacade implements DiscountPromotionFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createDiscountPromotionCollectorStrategy()
            ->collect($discountTransfer, $quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function createPromotionDiscount(DiscountPromotionTransfer $discountPromotionTransfer)
    {
        return $this->getFactory()
            ->createDiscountPromotionWriter()
            ->create($discountPromotionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function updatePromotionDiscount(DiscountPromotionTransfer $discountPromotionTransfer)
    {
        return $this->getFactory()
            ->createDiscountPromotionWriter()
            ->update($discountPromotionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return int
     */
    public function removePromotionFromDiscount(DiscountPromotionTransfer $discountPromotionTransfer): int
    {
        return $this->getFactory()
            ->createDiscountPromotionWriter()
            ->removePromotionFromDiscount($discountPromotionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDiscountPromotion
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscountPromotion($idDiscountPromotion)
    {
         return $this->getFactory()
             ->createDiscountPromotionReader()
             ->findDiscountPromotionByIdDiscountPromotion($idDiscountPromotion);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function expandDiscountConfigurationWithPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        return $this->getFactory()
            ->createDiscountPromotionReader()
            ->expandDiscountPromotion($discountConfiguratorTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDiscount
     *
     * @return bool
     */
    public function isDiscountWithPromotion($idDiscount)
    {
        return $this->getFactory()
            ->createDiscountPromotionReader()
            ->isDiscountWithPromotion($idDiscount);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscount($idDiscount)
    {
        return $this->getFactory()
            ->createDiscountPromotionReader()
            ->findDiscountPromotionByIdDiscount($idDiscount);
    }
}
