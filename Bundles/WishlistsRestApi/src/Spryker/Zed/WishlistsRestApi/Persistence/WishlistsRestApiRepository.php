<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiPersistenceFactory getFactory()
 */
class WishlistsRestApiRepository extends AbstractRepository implements WishlistsRestApiRepositoryInterface
{
    protected const BATCH_SIZE = 200;

    /**
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlist[]
     */
    public function getWishlistEntitiesWithoutUuid(): array
    {
        return $this->getFactory()
            ->getWishlistPropelQuery()
            ->filterByUuid(null, Criteria::ISNULL)
            ->limit(static::BATCH_SIZE)
            ->find()
            ->getData();
    }

    /**
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItem[]
     */
    public function getWishlistItemEntitiesWithoutUuid(): array
    {
        return $this->getFactory()
            ->getWishlistItemPropelQuery()
            ->filterByUuid(null, Criteria::ISNULL)
            ->limit(static::BATCH_SIZE)
            ->find()
            ->getData();
    }
}
