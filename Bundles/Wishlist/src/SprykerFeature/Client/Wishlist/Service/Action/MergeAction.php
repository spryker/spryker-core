<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerFeature\Client\Wishlist\Service\Action;


use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

class MergeAction extends AbstractActionFactory
{
    const ZED_CONTROLLER_PERSISTENCE_ACTION = 'merge';

    /**
     * @var WishlistInterface
     */
    private $sessionWishlist;

    /**
     * @param TransferInterface $transfer
     *
     * @return MergeAction
     */
    public function setTransferObject(TransferInterface $transfer)
    {
        return $this;
    }

    protected function synchronizeSessionLayer()
    {
        if (!$this->customerTransfer instanceof CustomerInterface) {
            throw new \InvalidArgumentException('It is not possible to merge Wishlist with Database with a customer');
        }

        $this->sessionWishlist = $this->session->get(self::getWishlistSessionID());
        $this->sessionWishlist->setCustomer($this->customerTransfer);
    }

    protected function synchronizePersistingLayer()
    {
        $response = $this->client->call($this->getUrl(self::ZED_CONTROLLER_PERSISTENCE_ACTION), $this->sessionWishlist, null, true);

        if (!$response instanceof WishlistInterface) {
            throw new \InvalidArgumentException('Merge Response should be an instance of WishlistInterface');
        }

        $this->session->set(self::getWishlistSessionID(), $response);
        $this->setResponse($response);
    }

}
