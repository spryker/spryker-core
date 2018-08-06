<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

interface ShoppingListStorageRepositoryInterface
{
    /**
     * @param array $shippingListIds
     *
     * @return array
     */
    public function getCustomerReferencesByShippingListIds(array $shippingListIds): array;
}
