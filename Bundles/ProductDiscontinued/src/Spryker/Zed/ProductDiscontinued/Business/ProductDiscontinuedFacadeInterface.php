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
use Psr\Log\LoggerInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedBusinessFactory getFactory()
 */
interface ProductDiscontinuedFacadeInterface
{
    /**
     * Specification:
     *  - Mark concrete product as discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function markProductAsDiscontinued(
        ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
    ): ProductDiscontinuedResponseTransfer;

    /**
     * Specification:
     *  - Mark concrete product as not discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function unmarkProductAsDiscontinued(
        ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
    ): ProductDiscontinuedResponseTransfer;

    /**
     * Specification:
     *  - Find product discontinued by concrete product id.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function findProductDiscontinuedByProductId(int $idProduct): ProductDiscontinuedResponseTransfer;

    /**
     * Specification:
     *  - Find product discontinued by filters.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
    ): ProductDiscontinuedCollectionTransfer;

    /**
     * Specification:
     * - Saves product discontinued note.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedNoteResponseTransfer
     */
    public function saveDiscontinuedNote(
        ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
    ): ProductDiscontinuedNoteResponseTransfer;

    /**
     * Specification:
     * - Deactivates discontinued products when active until date passed.
     * - Remove discontinued flag for deactivated products.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function deactivateDiscontinuedProducts(?LoggerInterface $logger = null): void;

    /**
     * Specification:
     *  - Checks all items related products from cart change request if they are not discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkItemProductsIsNotDiscontinued(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;
}
