<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Wishlist\Session;

use Generated\Shared\Transfer\WishlistTransfer;
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
     * @return WishlistTransfer
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
     * @param WishlistTransfer $wishlist
     *
     * @return self
     */
    public function setWishlist(WishlistTransfer $wishlist)
    {
        $this->session->set(self::WISHLIST_SESSION_IDENTIFIER, $wishlist);

        return $this;
    }

}
