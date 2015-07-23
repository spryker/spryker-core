<?php

namespace SprykerFeature\Client\Wishlist\Service\Session;

use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WishlistSession
{
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getCustomer()
    {
        return (new CustomerTransfer())->setIdCustomer(1);
    }
}
