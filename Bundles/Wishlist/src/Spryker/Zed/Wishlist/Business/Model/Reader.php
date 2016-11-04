<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\Map\SpyWishlistItemTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Propel\PropelFilterCriteria;
use Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException;
use Spryker\Zed\Wishlist\Business\Transfer\WishlistTransferMapperInterface;
use Spryker\Zed\Wishlist\Dependency\Facade\WishlistToLocaleInterface;
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
     * @var \Spryker\Zed\Wishlist\Dependency\Facade\WishlistToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Wishlist\Business\Transfer\WishlistTransferMapperInterface $transferMapper
     * @param \Spryker\Zed\Wishlist\Dependency\Facade\WishlistToLocaleInterface $localeFacade
     */
    public function __construct(
        WishlistQueryContainerInterface $queryContainer,
        WishlistTransferMapperInterface $transferMapper,
        WishlistToLocaleInterface $localeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->transferMapper = $transferMapper;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlistByName(WishlistTransfer $wishlistTransfer)
    {
        $wishlistTransfer->requireFkCustomer();
        $wishlistTransfer->requireName();

        $wishlistEntity = $this->getWishlistEntityByCustomerIdAndName(
            $wishlistTransfer->getFkCustomer(),
            $wishlistTransfer->getName()
        );

        return $this->transferMapper->convertWishlist($wishlistEntity);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer
     */
    public function getWishlistOverview(WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer)
    {
        $wishlistOverviewRequestTransfer->requireWishlist();

        $wishlistTransfer = $wishlistOverviewRequestTransfer->getWishlist();
        $wishlistTransfer->requireFkCustomer();
        $wishlistTransfer->requireName();

        $responseTransfer = (new WishlistOverviewResponseTransfer())
            ->setWishlist($wishlistTransfer)
            ->setItemsTotal(0);

        $wishlistEntity = $this->queryContainer
            ->queryWishlistByCustomerId($wishlistTransfer->getFkCustomer())
            ->filterByName($wishlistTransfer->getName())
            ->findOne();

        if (!$wishlistEntity) {
            return $responseTransfer;
        }

        $wishlistTransfer = $this->transferMapper->convertWishlist($wishlistEntity);

        $wishlistOverviewRequestTransfer->setWishlist($wishlistTransfer);

        $responseTransfer->setItems(new ArrayObject(
            $this->getWishlistOverviewItems($wishlistOverviewRequestTransfer)
        ));

        $totalItemsCount = $this->getTotalItemCount($wishlistTransfer->getIdWishlist());

        $responseTransfer
            ->setWishlist($wishlistTransfer)
            ->setItemsTotal($totalItemsCount);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer[]
     */
    protected function getWishlistOverviewItems(WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer)
    {
        $wishlistOverviewRequestTransfer->requireWishlist();
        $wishlistOverviewRequestTransfer->getWishlist()->requireIdWishlist();

        $filterCriteria = $this->getFilterCriteria($wishlistOverviewRequestTransfer->getFilter());
        $itemCollection = $this->queryContainer
            ->queryItemsByWishlistId($wishlistOverviewRequestTransfer->getWishlist()->getIdWishlist())
            ->mergeWith($filterCriteria->toCriteria())
            ->find();

        return $this->transferMapper->convertWishlistItemCollection($itemCollection);
    }

    /**
     * @param int $idWishlist
     *
     * @return int
     */
    protected function getTotalItemCount($idWishlist)
    {
        return $this->queryContainer->queryWishlistItem()
            ->filterByFkWishlist($idWishlist)
            ->count();
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
                'Wishlist: %s for customer with id: %s not found',
                $name,
                $idCustomer
            ));
        }

        return $wishlistEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filter
     *
     * @return \Spryker\Zed\Propel\PropelFilterCriteria
     */
    protected function getFilterCriteria(FilterTransfer $filter = null)
    {
        return new PropelFilterCriteria(
            $this->mergeDefaultFilter($filter)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filter
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function mergeDefaultFilter(FilterTransfer $filter = null)
    {
        $defaultFilter = (new FilterTransfer())
            ->setOrderDirection(Criteria::DESC)
            ->setOrderBy(SpyWishlistItemTableMap::COL_CREATED_AT)
            ->setLimit(10)
            ->setOffset(0);

        if ($filter === null) {
            return $defaultFilter;
        }

        $filterData = $filter->toArray();
        $defaultFilterData = $defaultFilter->toArray();
        $isEmpty = empty(array_filter(
            array_values($filterData)
        ));

        $data = array_merge(
            $defaultFilterData,
            $filterData
        );

        if ($isEmpty) {
            $data = array_merge(
                $filterData,
                $defaultFilterData
            );
        }

        return (new FilterTransfer())
            ->fromArray($data);
    }

    /**
     * @param int $idCustomer
     * @param string $name
     *
     * @return bool
     */
    protected function hasCustomerWishlist($idCustomer, $name)
    {
        return $this->queryContainer
            ->queryWishlistByCustomerId($idCustomer)
            ->filterByName($name)
            ->count() > 0;
    }

}
