<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUuidWriter;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUuidWriterInterface;

/**
 * @method \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\WishlistsRestApi\WishlistsRestApiConfig getConfig()
 */
class WishlistsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUuidWriterInterface
     */
    public function createWishlistUuidWriter(): WishlistUuidWriterInterface
    {
        return new WishlistUuidWriter($this->getEntityManager());
    }
}
