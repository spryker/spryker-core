<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode\Zed;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Spryker\Client\CartCodesRestApi\Dependency\Client\CartCodesRestApiToZedRequestClientInterface;

class CartCodeZedStub implements CartCodeZedStubInterface
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
     * @uses \Spryker\Zed\CartCode\Communication\Controller\GatewayController::addCartCodeAction()
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function addCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CartCodeResponseTransfer $cartCodeResponseTransfer */
        $cartCodeResponseTransfer = $this->zedStubClient->call('/cart-code/gateway/add-cart-code', $cartCodeRequestTransfer);

        return $cartCodeResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\CartCode\Communication\Controller\GatewayController::removeCartCodeAction()
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
     * @uses \Spryker\Zed\CartCode\Communication\Controller\GatewayController::clearCartCodesAction()
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function clearCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CartCodeResponseTransfer $cartCodeResponseTransfer */
        $cartCodeResponseTransfer = $this->zedStubClient->call('/cart-codes-rest-api/gateway/clear-cart-codes', $cartCodeRequestTransfer);

        return $cartCodeResponseTransfer;
    }
}
