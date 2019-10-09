<?php

namespace Spryker\Zed\CartCodesRestApi\Business\CartCodeAdder;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeInterface;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToQuoteFacadeInterface;

class CartCodeAdder implements CartCodeAdderInterface
{
    /**
     * @var CartCodesRestApiToCartCodeFacadeInterface
     */
    protected $cartCodeFacade;

    /**
     * @var CartCodesRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param CartCodesRestApiToCartCodeFacadeInterface $cartCodeFacade
     * @param CartCodesRestApiToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        CartCodesRestApiToCartCodeFacadeInterface $cartCodeFacade,
        CartCodesRestApiToQuoteFacadeInterface $quoteFacade
    ) {
        $this->cartCodeFacade = $cartCodeFacade;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $voucherCode): CartCodeOperationResultTransfer
    {
        $quoteTransfer = $this->quoteFacade->findQuoteByUuid($quoteTransfer);

        return $this->cartCodeFacade->addCandidate($quoteTransfer, $voucherCode);
    }
}
