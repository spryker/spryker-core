<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerFeature\Client\Wishlist\Service\Action;


use Generated\Shared\Customer\CustomerInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

class MergeAction extends AbstractActionFactory
{
    private $sessionWishlist;

    public function setTransferObject(TransferInterface $transfer)
    {
        return $this;
    }

    protected function handleSession()
    {
        if (!$this->customerTransfer instanceof CustomerInterface) {
            throw new \InvalidArgumentException("It is not possible to merge Wishlist with Database with a customer");
        }

        $this->sessionWishlist = $this->session->get(self::WISHLIST_SESSION_IDENTIFIER);
        $this->sessionWishlist->setCustomer($this->customerTransfer);
    }

    protected function handleZed()
    {
        $response = $this->client->call($this->getUrl('merge'), $this->sessionWishlist);
        $this->setResponse($response);
    }

}
