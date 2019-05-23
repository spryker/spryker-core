<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Model\Checker;

use Generated\Shared\Transfer\CartChangeTransfer;

interface CartChangeTransferNormalizerPreCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $normalizableFields
     *
     * @return bool
     */
    public function hasNormalizableItems(CartChangeTransfer $cartChangeTransfer, array $normalizableFields): bool;
}
