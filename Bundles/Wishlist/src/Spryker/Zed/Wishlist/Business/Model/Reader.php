<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\WishlistTransfer;
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
     * @param WishlistQueryContainerInterface $queryContainer
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
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist($idCustomer)
    {
        $wishlistTransfer = new WishlistTransfer();
        $itemCollection = $this->queryContainer
            ->queryWishlistByCustomerId($idCustomer)
            ->find();

        $items = $this->transferMapper->convertWishlistItemCollection($itemCollection);
        $wishlistTransfer->setItems(new ArrayObject($items));

        return $wishlistTransfer;
    }

}
