<?php

namespace Spryker\Zed\CartCodesRestApi\Business\CartCodeAdder;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeInterface;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeInterface;

class CartCodeAdder implements CartCodeAdderInterface
{
    /**
     * @var CartCodesRestApiToCartCodeFacadeInterface
     */
    protected $cartCodeFacade;

    /**
     * @var CartCodesRestApiToCartsRestApiFacadeInterface
     */
    protected $cartsRestApi;

    /**
     * @param CartCodesRestApiToCartCodeFacadeInterface $cartCodeFacade
     * @param CartCodesRestApiToCartsRestApiFacadeInterface $cartsRestApi
     */
    public function __construct(
        CartCodesRestApiToCartCodeFacadeInterface $cartCodeFacade,
        CartCodesRestApiToCartsRestApiFacadeInterface $cartsRestApi
    ) {
        $this->cartCodeFacade = $cartCodeFacade;
        $this->cartsRestApi = $cartsRestApi;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $voucherCode): CartCodeOperationResultTransfer
    {
        $quoteTransfer = $this->cartsRestApi->findQuoteByUuid($quoteTransfer)->getQuoteTransfer();

        return $this->cartCodeFacade->addCandidate($quoteTransfer, $voucherCode);
    }
}
