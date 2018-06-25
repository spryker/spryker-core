<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnit;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;

interface ProductPackagingUnitClientInterface
{
    /**
     * Specification:
     * - Expands the provided persistent cart change transfer's single item with the provided sales unit ID.
     * - Throws exception if multiple items are provided within the cart change transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @throws \Spryker\Client\ProductPackagingUnit\Exception\InvalidItemCountException
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandproductPackagingUnitQuantityForPersistentCartChange(PersistentCartChangeTransfer $persistentCartChangeTransfer, array $params = []): PersistentCartChangeTransfer;

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
     * @throws \Spryker\Client\ProductPackagingUnit\Exception\InvalidItemCountException
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandproductPackagingUnitQuantityForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer;
}
