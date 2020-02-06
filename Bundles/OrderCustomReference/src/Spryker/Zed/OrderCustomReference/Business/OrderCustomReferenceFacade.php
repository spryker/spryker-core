<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business;

use Generated\Shared\Transfer\QuoteTransfer;
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
     * @param string $orderCustomReference
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function saveOrderCustomReference(string $orderCustomReference, int $idSalesOrder): void
    {
        $this->getFactory()
            ->createOrderCustomReferenceSaver()
            ->saveOrderCustomReference($orderCustomReference, $idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getOrderCustomReferenceQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->createQuoteFieldsProvider()
            ->getOrderCustomReferenceQuoteFieldsAllowedForSaving($quoteTransfer);
    }
}
