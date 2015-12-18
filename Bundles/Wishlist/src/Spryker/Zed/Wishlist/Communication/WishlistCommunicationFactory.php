<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Wishlist\WishlistConfig;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainer;

/**
 * @method WishlistConfig getConfig()
 * @method WishlistQueryContainer getQueryContainer()
 */
class WishlistCommunicationFactory extends AbstractCommunicationFactory
{
}
