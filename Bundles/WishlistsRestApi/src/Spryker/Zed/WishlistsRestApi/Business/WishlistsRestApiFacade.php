<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\WishlistsRestApi\Business\WishlistsRestApiBusinessFactory getFactory()
 */
class WishlistsRestApiFacade extends AbstractFacade implements WishlistsRestApiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function updateWishlistsUuid(): void
    {
        $this->getFactory()
            ->createWishlistUuidWriter()
            ->updateWishlistsUuid();
    }
}
