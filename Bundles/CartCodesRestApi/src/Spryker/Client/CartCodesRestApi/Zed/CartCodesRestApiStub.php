<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCodesRestApi\Zed;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Spryker\Client\CartCodesRestApi\Dependency\Client\CartCodesRestApiToZedRequestClientInterface;

class CartCodesRestApiStub implements CartCodesRestApiStubInterface
{
    /**
     * @var \Spryker\Client\CartCodesRestApi\Dependency\Client\CartCodesRestApiToZedRequestClientInterface
     */
    protected $zedStubClient;

    /**
     * @param \Spryker\Client\CartCodesRestApi\Dependency\Client\CartCodesRestApiToZedRequestClientInterface $zedStubClient
     */
    public function __construct(CartCodesRestApiToZedRequestClientInterface $zedStubClient)
    {
        $this->zedStubClient = $zedStubClient;
    }

    /**
     * @uses \Spryker\Zed\CartCodesRestApi\Communication\Controller\GatewayController::addCartCodeAction()
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function addCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CartCodeResponseTransfer $cartCodeResponseTransfer */
        $cartCodeResponseTransfer = $this->zedStubClient->call('/cart-codes-rest-api/gateway/add-cart-code', $cartCodeRequestTransfer);

        return $cartCodeResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\CartCodesRestApi\Communication\Controller\GatewayController::removeCartCodeAction()
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function removeCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CartCodeResponseTransfer $cartCodeResponseTransfer */
        $cartCodeResponseTransfer = $this->zedStubClient->call('/cart-codes-rest-api/gateway/remove-cart-code', $cartCodeRequestTransfer);

        return $cartCodeResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\CartCodesRestApi\Communication\Controller\GatewayController::removeCartCodeFromQuoteAction()
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function removeCartCodeFromQuote(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CartCodeResponseTransfer $cartCodeResponseTransfer */
        $cartCodeResponseTransfer = $this->zedStubClient->call('/cart-codes-rest-api/gateway/remove-cart-code-from-quote', $cartCodeRequestTransfer);

        return $cartCodeResponseTransfer;
    }
}
