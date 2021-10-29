<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Expander;

use Generated\Shared\Transfer\WishlistItemTransfer;

interface WishlistItemExpanderInterface
{
    /**
     * @phpstan-param array<string, mixed> $params
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWithProductConfiguration(WishlistItemTransfer $wishlistItemTransfer, array $params): WishlistItemTransfer;
}
