<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\CartChangeRequestExpander;

use Generated\Shared\Transfer\CartChangeTransfer;

interface CartChangeRequestExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addItemsRequestExpand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer;

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function removeItemRequestExpand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer;
}
