<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedBusinessFactory getFactory()
 */
class ProductDiscontinuedFacade extends AbstractFacade implements ProductDiscontinuedFacadeInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
}
