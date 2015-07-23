<?php

namespace SprykerFeature\Client\Wishlist\Service\Zed;

use Generated\Shared\CustomerCheckoutConnector\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

class WishlistStub
{
    protected $client;

    protected $url_pattern = "/wishlist/gateway/%s";

    public function __construct(ZedRequestClient $client)
    {
        $this->client = $client;
    }

    public function findWishlistByCustomer(CustomerInterface $customerTransfer)
    {
        return $this->client->call($this->getUrl('get-wishlist'), $customerTransfer, [], true);
    }

    public function saveItems(WishlistChangeInterface $itemTransfer)
    {
        $this->client->call($this->getUrl('save'), $itemTransfer);
        return;
    }

    public function removeItem(WishlistItemInterface $itemTransfer)
    {
        $this->client->call($this->getUrl('remove'), $itemTransfer);
        return;
    }


    private function getUrl($action)
    {
        return sprintf($this->url_pattern, $action);
    }
}
