<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface getEntityManager()
 */
class ProductDiscontinuedFacade extends AbstractFacade implements ProductDiscontinuedFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function markProductAsDiscontinued(
        ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
    ): ProductDiscontinuedResponseTransfer {
        return $this->getFactory()
            ->createProductDiscontinuedWriter()
            ->create($productDiscontinueRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function unmarkProductAsDiscontinued(
        ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
    ): ProductDiscontinuedResponseTransfer {
        return $this->getFactory()
            ->createProductDiscontinuedWriter()
            ->delete($productDiscontinueRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function findProductDiscontinuedByProductId(int $idProduct): ProductDiscontinuedResponseTransfer
    {
        return $this->getFactory()
            ->createProductDiscontinuedReader()
            ->findProductDiscontinuedByProductId($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return bool
     */
    public function areAllConcreteProductsDiscontinued(array $productIds): bool
    {
        return $this->getFactory()
            ->createProductDiscontinuedReader()
            ->areAllConcreteProductsDiscontinued($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return bool
     */
    public function isAnyProductConcreteDiscontinued(array $productConcreteIds): bool
    {
        return $this->getRepository()
            ->isAnyProductConcreteDiscontinued($productConcreteIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
    ): ProductDiscontinuedCollectionTransfer {
        return $this->getFactory()
            ->createProductDiscontinuedReader()
            ->findProductDiscontinuedCollection($criteriaFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedNoteResponseTransfer
     */
    public function saveDiscontinuedNote(
        ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
    ): ProductDiscontinuedNoteResponseTransfer {
        return $this->getFactory()
            ->createProductDiscontinuedNoteWriter()
            ->saveNote($discontinuedNoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function deactivateDiscontinuedProducts(?LoggerInterface $logger = null): void
    {
        $this->getFactory()
            ->createProductDiscontinuedDeactivator($logger)
            ->deactivate();
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
    public function checkItemProductsIsNotDiscontinued(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFactory()
            ->createCartChangePreCheck()
            ->checkCartItems($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkShoppingListItemProductIsNotDiscontinued(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer
    {
        return $this->getFactory()
            ->createShoppingListAddItemPreCheck()
            ->checkShoppingListItemProductIsNotDiscontinued($shoppingListItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductIsNotDiscontinued(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer
    {
        return $this->getFactory()
            ->createWishlistAddItemPreCheck()
            ->checkWishlistItemProductIsNotDiscontinued($wishlistItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return int[]
     */
    public function findProductAbstractIdsWithDiscontinuedConcrete(): array
    {
        return $this->getFactory()
            ->createProductDiscontinuedReader()
            ->findProductAbstractIdsWithDiscontinuedConcrete();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkProductsInCheckoutAreNotDiscontinued(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        return $this->getFactory()
            ->createProductDiscontinuedCheckoutPreConditionChecker()
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);
    }
}
