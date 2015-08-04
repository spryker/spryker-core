<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service\Session;

use Generated\Shared\Transfer\WishlistTransfer;
use Generated\Shared\Wishlist\WishlistInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WishlistSession implements WishlistSessionInterface
{
    const WISHLIST_SESSION_IDENTIFIER = 'wishlist session identifier';

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return WishlistInterface
     */
    public function getWishlist()
    {
        $wishlistTransfer = new WishlistTransfer();

        if ($this->session->has(self::WISHLIST_SESSION_IDENTIFIER)) {
            return $this->session->get(self::WISHLIST_SESSION_IDENTIFIER, $wishlistTransfer);
        }

        return $wishlistTransfer;
    }

    /**
     * @param WishlistInterface $wishlist
     *
     * @return $this
     */
    public function setWishlist(WishlistInterface $wishlist)
    {
        $this->session->set(self::WISHLIST_SESSION_IDENTIFIER, $wishlist);

        return $this;
    }
}
