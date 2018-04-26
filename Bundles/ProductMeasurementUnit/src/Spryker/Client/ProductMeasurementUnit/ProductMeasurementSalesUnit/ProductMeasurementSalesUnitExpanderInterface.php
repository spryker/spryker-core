<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnit\ProductMeasurementSalesUnit;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;

interface ProductMeasurementSalesUnitExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandSingleItemQuantitySalesUnitForPersistentCartChange(PersistentCartChangeTransfer $cartChangeTransfer, array $params = []): PersistentCartChangeTransfer;

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandSingleItemQuantitySalesUnitForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer;
}
