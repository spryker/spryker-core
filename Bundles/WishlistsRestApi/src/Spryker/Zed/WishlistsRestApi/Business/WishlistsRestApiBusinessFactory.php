<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistWriter;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistWriterInterface;
use Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemsWriter;
use Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemsWriterInterface;

/**
 * @method \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\WishlistsRestApi\WishlistsRestApiConfig getConfig()
 * @method \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiRepository getRepository()
 */
class WishlistsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistWriterInterface
     */
    public function createWishlistWriter(): WishlistWriterInterface
    {
        return new WishlistWriter(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemsWriterInterface
     */
    public function createWishlistItemWriter(): WishlistItemsWriterInterface
    {
        return new WishlistItemsWriter(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }
}
