<?php

namespace Spryker\Client\CartCodesRestApi\CartCodeAdder;

use Generated\Shared\Transfer\AddCandidateRequestTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed\CartCodesRestApiStubInterface;

class CartCodeAdder implements CartCodeAdderInterface
{
    /**
     * @var CartCodesRestApiStubInterface
     */
    protected $cartCodesRestApiStub;

    /**
     * @param CartCodesRestApiStubInterface $cartCodesRestApiStub
     */
    public function __construct(CartCodesRestApiStubInterface $cartCodesRestApiStub)
    {
        $this->cartCodesRestApiStub = $cartCodesRestApiStub;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $voucherCode): CartCodeOperationResultTransfer
    {
        return $this->cartCodesRestApiStub
            ->addCandidate($this->prepareAddCandidateRequestTransfer($quoteTransfer, $voucherCode));
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return AddCandidateRequestTransfer
     */
    protected function prepareAddCandidateRequestTransfer(QuoteTransfer $quoteTransfer, string $voucherCode): AddCandidateRequestTransfer
    {
        return (new AddCandidateRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setVoucherCode($voucherCode);
    }
}
