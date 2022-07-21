<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnit\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;

interface ProductPackagingUnitAmountExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandAmountForPersistentCartChange(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        array $params = []
    ): PersistentCartChangeTransfer;

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandAmountForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer;
}
