<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionBusinessFactory getFactory()
 */
class DiscountPromotionFacade extends AbstractFacade implements DiscountPromotionFacadeInterface
{

    /**
     * @api
     *
     * {@inheritdoc}
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
     *
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function savePromotionDiscount(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        return $this->getFactory()
            ->createDiscountPromotionWriter()
            ->save($discountConfiguratorTransfer);
    }

    /**
     *
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function updatePromotionDiscount(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        return $this->getFactory()
            ->createDiscountPromotionWriter()
            ->update($discountConfiguratorTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function expandDiscountConfigurationWithPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        return $this->getFactory()->createDiscountPromotionReader()->expandDiscountPromotion($discountConfiguratorTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param int $idDiscount
     *
     * @return bool
     */
    public function isDiscountWithPromotion($idDiscount)
    {
        return $this->getFactory()->createDiscountPromotionReader()->isDiscountWithPromotion($idDiscount);
    }

}
