<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business;

interface WishlistsRestApiFacadeInterface
{
    /**
     * Specification:
     *  - Updates existing wishlist records in DB with generated UUID value.
     *
     * @api
     *
     * @deprecated Use Spryker\Zed\UtilUuidGenerator\Communication\Console\UuidGeneratorConsole instead.
     *
     * @return void
     */
    public function updateWishlistsUuid(): void;
}
