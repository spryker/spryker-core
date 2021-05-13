<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Generated\Shared\Transfer\WishlistCollectionTransfer;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\Map\SpyWishlistItemTableMap;
use Orm\Zed\Wishlist\Persistence\Map\SpyWishlistTableMap;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery;
use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistPersistenceFactory getFactory()
 */
class WishlistRepository extends AbstractRepository implements WishlistRepositoryInterface
{
    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\WishlistCollectionTransfer
     */
    public function getByCustomerReference(string $customerReference): WishlistCollectionTransfer
    {
        /** @var \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery $wishlistQuery */
        $wishlistQuery = $this->getFactory()->createWishlistQuery()
            ->useSpyCustomerQuery()
                ->filterByCustomerReference($customerReference)
            ->endUse();

        $wishlistEntities = $wishlistQuery
            ->leftJoinWithSpyWishlistItem()
                ->withColumn('count(*)', WishlistTransfer::NUMBER_OF_ITEMS)
            ->groupByIdWishlist()
            ->find();

        if (!$wishlistEntities->count()) {
            return new WishlistCollectionTransfer();
        }

        return $this->getFactory()->createWishlistMapper()
            ->mapWishlistEntitiesToWishlistCollectionTransfer($wishlistEntities, new WishlistCollectionTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|null
     */
    public function findWishlistByFilter(WishlistFilterTransfer $wishlistFilterTransfer): ?WishlistTransfer
    {
        $alias = uniqid('swi_');
        $numberOfItemsQueryString = sprintf(
            '(SELECT count(*) FROM %s %s WHERE %s=%s )',
            SpyWishlistItemTableMap::TABLE_NAME,
            $alias,
            SpyWishlistTableMap::COL_ID_WISHLIST,
            str_replace(
                SpyWishlistItemTableMap::TABLE_NAME,
                $alias,
                SpyWishlistItemTableMap::COL_FK_WISHLIST
            )
        );

        $wishlistQuery = $this->getFactory()
            ->createWishlistQuery()
            ->leftJoinWithSpyWishlistItem()
                ->withColumn($numberOfItemsQueryString, WishlistTransfer::NUMBER_OF_ITEMS);

        $wishlistEntityCollection = $this->applyFilters($wishlistQuery, $wishlistFilterTransfer)
            ->find();

        if (!$wishlistEntityCollection->count()) {
            return null;
        }

        return $this->getFactory()
            ->createWishlistMapper()
            ->mapWishlistEntityToWishlistTransferIncludingWishlistItems(
                $wishlistEntityCollection->getFirst(),
                new WishlistTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemCriteriaTransfer $wishlistItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|null
     */
    public function findWishlistItem(WishlistItemCriteriaTransfer $wishlistItemCriteriaTransfer): ?WishlistItemTransfer
    {
        $wishlistItemQuery = $this->getFactory()
            ->createWishlistItemQuery();

        $wishlistItemQuery = $this->applyWishlistItemFilters($wishlistItemCriteriaTransfer, $wishlistItemQuery);
        $wishlistItemEntity = $wishlistItemQuery->findOne();

        if (!$wishlistItemEntity) {
            return null;
        }

        return $this->getFactory()
            ->createWishlistMapper()
            ->mapWishlistItemEntityToWishlistItemTransfer($wishlistItemEntity, new WishlistItemTransfer());
    }

    /**
     * @phpstan-param \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery<mixed> $wishlistQuery
     *
     * @phpstan-return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery<mixed>
     *
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery $wishlistQuery
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    protected function applyFilters(SpyWishlistQuery $wishlistQuery, WishlistFilterTransfer $wishlistFilterTransfer): SpyWishlistQuery
    {
        if ($wishlistFilterTransfer->getIdCustomer()) {
            $wishlistQuery->filterByFkCustomer($wishlistFilterTransfer->getIdCustomer());
        }
        if ($wishlistFilterTransfer->getName()) {
            $wishlistQuery->filterByName($wishlistFilterTransfer->getName());
        }
        if ($wishlistFilterTransfer->getUuid()) {
            $wishlistQuery->filterByUuid($wishlistFilterTransfer->getUuid());
        }

        return $wishlistQuery;
    }

    /**
     * @phpstan-param \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery<mixed> $wishlistItemQuery
     *
     * @phpstan-return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery<mixed>
     *
     * @param \Generated\Shared\Transfer\WishlistItemCriteriaTransfer $wishlistItemCriteriaTransfer
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery $wishlistItemQuery
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    protected function applyWishlistItemFilters(
        WishlistItemCriteriaTransfer $wishlistItemCriteriaTransfer,
        SpyWishlistItemQuery $wishlistItemQuery
    ): SpyWishlistItemQuery {
        if ($wishlistItemCriteriaTransfer->getIdWishlistItem()) {
            /** @var int $idWishlistItem */
            $idWishlistItem = $wishlistItemCriteriaTransfer->getIdWishlistItem();
            $wishlistItemQuery->filterByIdWishlistItem($idWishlistItem);
        }

        return $wishlistItemQuery;
    }
}
