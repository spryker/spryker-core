<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\Reader;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\ReaderInterface;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUuidWriter;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUuidWriterInterface;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\Writer;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WriterInterface;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface;
use Spryker\Zed\WishlistsRestApi\WishlistsRestApiDependencyProvider;

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

    /**
     * @return \Spryker\Zed\WishlistsRestApi\Business\Wishlist\ReaderInterface
     */
    public function createReader(): ReaderInterface
    {
        return new Reader(
            $this->getWishlistFacade()
        );
    }

    /**
     * @return \Spryker\Zed\WishlistsRestApi\Business\Wishlist\WriterInterface
     */
    public function createWriter(): WriterInterface
    {
        return new Writer(
            $this->getWishlistFacade()
        );
    }

    /**
     * @return \Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface
     */
    public function getWishlistFacade(): WishlistsRestApiToWishlistFacadeInterface
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::FACADE_WISHLIST);
    }
}
