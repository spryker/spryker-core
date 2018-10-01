<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use ArrayObject;

interface WishlistRepositoryInterface
{
    /**
     * @param string $customerReference
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\WishlistTransfer[]
     */
    public function findByCustomerReference(string $customerReference): ArrayObject;
}
