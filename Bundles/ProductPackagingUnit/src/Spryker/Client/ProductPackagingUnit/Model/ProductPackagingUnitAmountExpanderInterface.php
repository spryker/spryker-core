<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnit\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;

interface ProductPackagingUnitAmountExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandProductPackagingUnitAmountForPersistentCartChange(PersistentCartChangeTransfer $persistentCartChangeTransfer, array $params = []): PersistentCartChangeTransfer;

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandProductPackagingUnitAmountForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer;
}
