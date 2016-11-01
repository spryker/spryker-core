<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\Map\SpyWishlistItemTableMap;
use Spryker\Zed\Propel\PropelFilterCriteria;
use Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException;
use Spryker\Zed\Wishlist\Business\Transfer\WishlistTransferMapperInterface;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface;

class Reader implements ReaderInterface
{

    /**
     * @var \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Wishlist\Business\Transfer\WishlistTransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @param \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Wishlist\Business\Transfer\WishlistTransferMapperInterface $transferMapper
     */
    public function __construct(
        WishlistQueryContainerInterface $queryContainer,
        WishlistTransferMapperInterface $transferMapper
    ) {
        $this->queryContainer = $queryContainer;
        $this->transferMapper = $transferMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getCustomerWishlistByName(WishlistTransfer $wishlistTransfer)
    {
        $wishlistTransfer->requireFkCustomer();
        $wishlistTransfer->requireName();

        $wishlistEntity = $this->getWishlistEntityByCustomerIdAndName(
            $wishlistTransfer->getFkCustomer(),
            $wishlistTransfer->getName()
        );

        $filter = $this->mergeDefaultFilter(
            $wishlistTransfer->getItemsFilter()
        );

        $filterCriteria = new PropelFilterCriteria($filter);
        $itemCollection = $this->queryContainer
            ->queryItemsByWishlistId($wishlistEntity->getIdWishlist())
            ->mergeWith($filterCriteria->toCriteria())
            ->find();

        $wishlistTransfer = $this->transferMapper->convertWishlist($wishlistEntity);
        $wishlistItems = $this->transferMapper->convertWishlistItemCollection($itemCollection);
        $wishlistTransfer->setItems(new ArrayObject(
            $wishlistItems
        ));

        return $wishlistTransfer;
    }

    /**
     * @param int $idWishlist
     *
     * @throws \Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlist
     */
    public function getWishlistEntityById($idWishlist)
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

    /**
     * @param int $idCustomer
     * @param string $name
     *
     * @throws \Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlist
     */
    public function getWishlistEntityByCustomerIdAndName($idCustomer, $name)
    {
        $wishlistEntity = $this->queryContainer
            ->queryWishlistByCustomerId($idCustomer)
            ->filterByName($name)
            ->findOne();

        if (!$wishlistEntity) {
            throw new MissingWishlistException(sprintf(
                'Wishlist: %s for customer: %s not found',
                $idCustomer,
                $name
            ));
        }

        return $wishlistEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filter
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    public function mergeDefaultFilter(FilterTransfer $filter = null)
    {
        $defaultFilter = (new FilterTransfer())
            ->setOrderDirection('DESC')
            ->setOrderBy(SpyWishlistItemTableMap::COL_CREATED_AT)
            ->setLimit(10)
            ->setOffset(0);

        if ($filter == null) {
            return $defaultFilter;
        }

        $filterData = $filter->toArray();
        $defaultFilterData = $defaultFilter->toArray();
        $data = array_merge($defaultFilterData, $filterData);

        return (new FilterTransfer())
            ->fromArray($data);
    }

}
