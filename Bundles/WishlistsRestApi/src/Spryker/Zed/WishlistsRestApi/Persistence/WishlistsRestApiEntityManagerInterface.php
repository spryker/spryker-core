<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Persistence;

use Spryker\Zed\Kernel\Persistence\EntityManager\EntityManagerInterface;

interface WishlistsRestApiEntityManagerInterface extends EntityManagerInterface
{
    /**
     * @return void
     */
    public function setEmptyWishlistUuids(): void;
}
