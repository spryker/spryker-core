<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Session;

use Generated\Shared\Transfer\WishlistTransfer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WishlistSession implements WishlistSessionInterface
{

    const WISHLIST_SESSION_IDENTIFIER = 'wishlist session identifier';

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer
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
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlist
     *
     * @return $this
     */
    public function setWishlist(WishlistTransfer $wishlist)
    {
        $this->session->set(self::WISHLIST_SESSION_IDENTIFIER, $wishlist);

        return $this;
    }

}
