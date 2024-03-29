<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;

interface GroupKeyPrefixItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartItemsWithGroupKeyPrefix(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer;
}
