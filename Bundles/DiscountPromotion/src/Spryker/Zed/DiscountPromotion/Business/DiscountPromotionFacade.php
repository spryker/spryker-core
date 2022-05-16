<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionCollectionTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\DiscountVoucherCheckResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionBusinessFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface getRepository()
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
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createDiscountPromotionCollectorStrategyComposite()
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
            ->createDiscountPromotionCreator()
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
            ->createDiscountPromotionUpdater()
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
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacade::getDiscountPromotionCollection()} instead.
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
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacade::getDiscountPromotionCollection()} instead.
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacade::getDiscountPromotionCollection()} instead.
     *
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByUuid(string $uuid): ?DiscountPromotionTransfer
    {
        return $this->getFactory()
            ->createDiscountPromotionReader()
            ->findDiscountPromotionByUuid($uuid);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateCartDiscountPromotions(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFactory()
            ->createCartValidator()
            ->validateCartDiscountPromotions($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionCollectionTransfer
     */
    public function getDiscountPromotionCollection(DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer): DiscountPromotionCollectionTransfer
    {
        return $this->getRepository()->getDiscountPromotionCollection($discountPromotionCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function filterDiscountPromotionItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()
            ->createDiscountPromotionItemFilter()
            ->filterDiscountPromotionItems($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return \Generated\Shared\Transfer\DiscountVoucherCheckResponseTransfer
     */
    public function checkVoucherCodeApplied(QuoteTransfer $quoteTransfer, string $voucherCode): DiscountVoucherCheckResponseTransfer
    {
        return $this->getFactory()
            ->createDiscountPromotionVoucherCodeApplicationChecker()
            ->check($quoteTransfer, $voucherCode);
    }
}
