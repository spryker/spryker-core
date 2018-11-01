<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnit;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;

interface ProductMeasurementUnitClientInterface
{
    /**
     * Specification:
     * - Expands the provided persistent cart change transfer's single item with the provided sales unit ID.
     * - Throws exception if multiple items are provided within the cart change transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @throws \Spryker\Client\ProductMeasurementUnit\Exception\InvalidItemCountException
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandSingleItemQuantitySalesUnitForPersistentCartChange(PersistentCartChangeTransfer $cartChangeTransfer, array $params = []): PersistentCartChangeTransfer;

    /**
     * Specification:
     * - Expands the provided cart change transfer's single item with the provided sales unit ID.
     * - Throws exception if multiple items are provided within the cart change transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @throws \Spryker\Client\ProductMeasurementUnit\Exception\InvalidItemCountException
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandSingleItemQuantitySalesUnitForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer;
}
