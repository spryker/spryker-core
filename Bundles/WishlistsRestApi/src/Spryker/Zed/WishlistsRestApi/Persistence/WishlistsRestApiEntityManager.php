<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiPersistenceFactory getFactory()
 */
class WishlistsRestApiEntityManager extends AbstractEntityManager implements WishlistsRestApiEntityManagerInterface
{
    protected const BATCH_SIZE = 200;

    /**
     * @return void
     */
    public function setEmptyWishlistUuids(): void
    {
        $wishlistQuery = $this->getFactory()->getWishlistPropelQuery();

        do {
            $wishlistEntities = $wishlistQuery
                ->filterByUuid(null, Criteria::ISNULL)
                ->limit(static::BATCH_SIZE)
                ->find();

            foreach ($wishlistEntities as $wishlistEntity) {
                $wishlistEntity->save();
            }
        } while ($wishlistEntities->count());
    }
}
