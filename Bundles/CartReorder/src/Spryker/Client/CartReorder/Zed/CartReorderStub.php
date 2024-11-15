<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartReorder\Zed;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Spryker\Client\CartReorder\Dependency\Client\CartReorderToZedRequestClientInterface;

class CartReorderStub implements CartReorderStubInterface
{
    /**
     * @var \Spryker\Client\CartReorder\Dependency\Client\CartReorderToZedRequestClientInterface
     */
    protected CartReorderToZedRequestClientInterface $zedRequestClient;

    /**
     * @param \Spryker\Client\CartReorder\Dependency\Client\CartReorderToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CartReorderToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\CartReorder\Communication\Controller\GatewayController::reorderAction()
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function reorder(CartReorderRequestTransfer $cartReorderRequestTransfer): CartReorderResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer */
        $cartReorderResponseTransfer = $this->zedRequestClient->call(
            '/cart-reorder/gateway/reorder',
            $cartReorderRequestTransfer,
        );

        return $cartReorderResponseTransfer;
    }
}
