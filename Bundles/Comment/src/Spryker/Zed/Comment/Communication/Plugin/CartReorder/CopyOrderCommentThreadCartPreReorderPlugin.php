<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Comment\Business\CommentFacadeInterface getFacade()
 * @method \Spryker\Zed\Comment\Business\CommentBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\Comment\CommentConfig getConfig()
 * @method \Spryker\Zed\Comment\Communication\CommentCommunicationFactory getFactory()
 */
class CopyOrderCommentThreadCartPreReorderPlugin extends AbstractPlugin implements CartPreReorderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `CartReorderRequestTransfer.isAmendment` to be set.
     * - Does nothing when `CartReorderRequestTransfer.isAmendment` is not provided.
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.quote` to be set.
     * - Copies `CartReorderTransfer.order.commentThread` from order to `CartReorderTransfer.quote.commentThread` if it is provided.
     * - Returns `CartReorderTransfer` with updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function preReorder(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        if ($cartReorderRequestTransfer->getIsAmendment()) {
            $commentThreadTransfer = $this->getBusinessFactory()
                ->createCommentThreadWriter()
                ->copyCommentThreadFromOrderToQuote($cartReorderTransfer->getOrderOrFail(), $cartReorderTransfer->getQuoteOrFail())
                ->getCommentThread();

            $cartReorderTransfer->getQuote()->setCommentThread($commentThreadTransfer);
        }

        return $cartReorderTransfer;
    }
}
