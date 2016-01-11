<?php

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

interface OrderReferenceGeneratorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateOrderReference(QuoteTransfer $quoteTransfer);

}
