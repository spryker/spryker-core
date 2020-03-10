<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business;

use Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\OrderCustomReference\Business\OrderCustomReferenceBusinessFactory getFactory()
 */
class OrderCustomReferenceFacade extends AbstractFacade implements OrderCustomReferenceFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function saveOrderCustomReferenceFromQuote(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): OrderCustomReferenceResponseTransfer
    {
        return $this->getFactory()
            ->createOrderCustomReferenceWriter()
            ->saveOrderCustomReferenceFromQuote($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function updateOrderCustomReference(string $orderCustomReference, OrderTransfer $orderTransfer): OrderCustomReferenceResponseTransfer
    {
        return $this->getFactory()
            ->createOrderCustomReferenceWriter()
            ->updateOrderCustomReference($orderCustomReference, $orderTransfer);
    }
}
