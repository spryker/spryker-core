<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Model;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Spryker\Zed\Propel\Business\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException;
use Spryker\Zed\Wishlist\Business\Exception\WishlistExistsException;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface;

class Writer implements WriterInterface
{

    /**
     * @var \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface $queryContainer
     */
    public function __construct(WishlistQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function createWishlist(WishlistTransfer $wishlistTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $this->assertWishlistUniqueName($wishlistTransfer);

        $wishlistEntity = new SpyWishlist();
        $data = $wishlistTransfer->toArray();
        unset($data[WishlistTransfer::ID_WISHLIST]);
        $wishlistEntity->fromArray($data);
        $wishlistEntity->save();

        $wishlistTransfer->setIdWishlist($wishlistEntity->getIdWishlist());

        $this->addItemCollection($wishlistTransfer);

        $this->queryContainer->getConnection()->commit();

        return $wishlistTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function updateWishlist(WishlistTransfer $wishlistTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $wishlistTransfer->requireIdWishlist();
        $wishListEntity = $this->getWishlistEntityById($wishlistTransfer->getIdWishlist());
        $this->assertWishlistUniqueNameWhenUpdating($wishlistTransfer);

        $wishListEntity->fromArray($wishlistTransfer->toArray());
        $wishListEntity->save();

        $this->queryContainer->getConnection()->commit();

        return $wishlistTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlist(WishlistTransfer $wishlistTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $wishlistTransfer->requireIdWishlist();
        $wishListEntity = $this->getWishlistEntityById($wishlistTransfer->getIdWishlist());

        $this->removeItemCollection($wishlistTransfer);
        $wishListEntity->delete();

        $this->queryContainer->getConnection()->commit();

        return $wishlistTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItemCollection(WishlistTransfer $wishlistTransfer)
    {
        $wishlistTransfer->requireIdWishlist();

        foreach ($wishlistTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setFkWishlist($wishlistTransfer->getIdWishlist());
            $this->addItem($itemTransfer);
        }

        return $wishlistTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItemCollection(WishlistTransfer $wishlistTransfer)
    {
        $wishlistTransfer->requireIdWishlist();

        $this->queryContainer->queryWishlistItem()
            ->filterByFkWishlist($wishlistTransfer->getIdWishlist())
            ->deleteAll();

        $wishlistTransfer->setItems(new \ArrayObject());

        return $wishlistTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function addItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        $this->assertWishlistItem($wishlistItemTransfer);

        $entity = $this->queryContainer->queryWishlistItem()
            ->filterByFkWishlist($wishlistItemTransfer->getFkWishlist())
            ->filterByFkProduct($wishlistItemTransfer->getFkProduct())
            ->findOneOrCreate();

        $entity->save();

        return $wishlistItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function removeItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        $this->assertWishlistItem($wishlistItemTransfer);

        $this->queryContainer->queryWishlistItem()
            ->filterByFkWishlist($wishlistItemTransfer->getFkWishlist())
            ->filterByFkProduct($wishlistItemTransfer->getFkProduct())
            ->delete();

        return $wishlistItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return void
     */
    protected function assertWishlistItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        $wishlistItemTransfer->getFkWishlist();
        $wishlistItemTransfer->requireFkProduct();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return void
     */
    protected function assertWishlistUniqueName(WishlistTransfer $wishlistTransfer)
    {
        $wishlistTransfer->requireName();
        $wishlistTransfer->requireFkCustomer();

        $query = $this->queryContainer->queryWishlist()
            ->filterByName($wishlistTransfer->getName())
            ->filterByFkCustomer($wishlistTransfer->getFkCustomer());

        $this->assertWishlistIsUnique($query, $wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return void
     */
    protected function assertWishlistUniqueNameWhenUpdating(WishlistTransfer $wishlistTransfer)
    {
        $wishlistTransfer->requireName();
        $wishlistTransfer->requireIdWishlist();

        $query = $this->queryContainer->queryWishlist()
            ->filterByName($wishlistTransfer->getName())
            ->filterByIdWishlist($wishlistTransfer->getIdWishlist(), Criteria::NOT_EQUAL);

        $this->assertWishlistIsUnique($query, $wishlistTransfer);
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery $query
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @throws \Spryker\Zed\Wishlist\Business\Exception\WishlistExistsException
     *
     * @return void
     */
    protected function assertWishlistIsUnique(SpyWishlistQuery $query, WishlistTransfer $wishlistTransfer)
    {
        $exists = $query->count();

        if ($exists) {
            throw new WishlistExistsException(sprintf(
                'Wishlist with name: %s for customer: %s already exists',
                $wishlistTransfer->getName(),
                $wishlistTransfer->getFkCustomer()
            ));
        }
    }

    /**
     * @param int $idWishlist
     *
     * @throws \Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlist
     */
    protected function getWishlistEntityById($idWishlist)
    {
        $wishListEntity = $this->queryContainer->queryWishlist()
            ->filterByIdWishlist($idWishlist)
            ->findOne();

        if (!$wishListEntity) {
            throw new MissingWishlistException(sprintf(
                'Wishlist with id %s not found',
                $idWishlist
            ));
        }

        return $wishListEntity;
    }

}
