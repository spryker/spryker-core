<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistDeleter;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistDeleterInterface;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistReader;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistReaderInterface;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUpdater;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUpdaterInterface;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUuidWriter;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUuidWriterInterface;
use Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemAdder;
use Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemAdderInterface;
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
     * @return \Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistReaderInterface
     */
    public function createWishlistReader(): WishlistReaderInterface
    {
        return new WishlistReader(
            $this->getWishlistFacade()
        );
    }

    /**
     * @return \Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUpdaterInterface
     */
    public function createWishlistUpdater(): WishlistUpdaterInterface
    {
        return new WishlistUpdater(
            $this->getWishlistFacade()
        );
    }

    /**
     * @return \Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistDeleterInterface
     */
    public function createWishlistDeleter(): WishlistDeleterInterface
    {
        return new WishlistDeleter(
            $this->getWishlistFacade()
        );
    }

    /**
     * @return \Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemAdderInterface
     */
    public function createWishlistItemAdder(): WishlistItemAdderInterface
    {
        return new WishlistItemAdder(
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
