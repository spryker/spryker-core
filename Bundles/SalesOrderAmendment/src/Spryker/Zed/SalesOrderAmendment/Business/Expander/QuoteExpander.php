<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\OrderReaderInterface;

class QuoteExpander implements QuoteExpanderInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Reader\OrderReaderInterface $orderReader
     */
    public function __construct(protected OrderReaderInterface $orderReader)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithOriginalOrder(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $orderTransfer = $this->orderReader->findCustomerOrder(
            $quoteTransfer->getAmendmentOrderReferenceOrFail(),
            $quoteTransfer->getCustomerReferenceOrFail(),
        );

        return $quoteTransfer->setOriginalOrder($orderTransfer);
    }
}
