<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Model;

interface ReaderInterface
{

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist($idCustomer);

}
