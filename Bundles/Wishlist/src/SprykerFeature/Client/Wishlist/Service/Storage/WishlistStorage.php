<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service\Storage;

use Generated\Shared\Wishlist\WishlistInterface;
use SprykerFeature\Client\Storage\Service\StorageClientInterface;

class WishlistStorage implements WishlistStorageInterface
{
    /**
     * @var StorageClientInterface
     */
    private $storageClient;

    /**
     * @param StorageClientInterface $storageClient
     */
    public function __construct(StorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @param WishlistInterface $wishlist
     *
     * @return WishlistInterface
     */
    public function expandProductDetails(WishlistInterface $wishlist)
    {
        //do merging
        return $this->storageClient->get('key'); //todo add
    }
}
