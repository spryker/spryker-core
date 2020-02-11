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
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionEntityManagerInterface getEntityManager()
 */
class DiscountPromotionFacade extends AbstractFacade implements DiscountPromotionFacadeInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * @param int $idDiscount
     *
     * @return void
     */
    public function removePromotionByIdDiscount(int $idDiscount): void
    {
        $this->getEntityManager()->removePromotionByIdDiscount($idDiscount);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
