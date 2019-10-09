<?php

namespace Spryker\Client\CartCodesRestApi\CartCodeAdder;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartCodeAdderInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $voucherCode): CartCodeOperationResultTransfer;
}
