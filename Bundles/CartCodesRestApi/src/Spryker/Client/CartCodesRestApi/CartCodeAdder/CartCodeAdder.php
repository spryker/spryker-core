<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCodesRestApi\CartCodeAdder;

use Generated\Shared\Transfer\AddCandidateRequestTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed\CartCodesRestApiStubInterface;

class CartCodeAdder implements CartCodeAdderInterface
{
    /**
     * @var \Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed\CartCodesRestApiStubInterface
     */
    protected $cartCodesRestApiStub;

    /**
     * @param \Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed\CartCodesRestApiStubInterface $cartCodesRestApiStub
     */
    public function __construct(CartCodesRestApiStubInterface $cartCodesRestApiStub)
    {
        $this->cartCodesRestApiStub = $cartCodesRestApiStub;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $voucherCode): CartCodeOperationResultTransfer
    {
        return $this->cartCodesRestApiStub
            ->addCandidate($this->prepareAddCandidateRequestTransfer($quoteTransfer, $voucherCode));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return \Generated\Shared\Transfer\AddCandidateRequestTransfer
     */
    protected function prepareAddCandidateRequestTransfer(QuoteTransfer $quoteTransfer, string $voucherCode): AddCandidateRequestTransfer
    {
        return (new AddCandidateRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setVoucherCode($voucherCode);
    }
}
