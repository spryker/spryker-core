<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerFeature\Client\Wishlist\Service\Action;


use Generated\Shared\Wishlist\WishlistChangeInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

class GetAction extends AbstractActionFactory
{
    const ZED_CONTROLLER_PERSISTENCE_ACTION = 'get-wishlist';

    /**
     * @param TransferInterface $transfer
     *
     * @return GetAction
     */
    public function setTransferObject(TransferInterface $transfer)
    {
        return $this;
    }

    protected function synchronizeSessionLayer()
    {
        $response = $this->session->get(self::getWishlistSessionID());

        $this->setResponse($response);
    }

    protected function synchronizePersistingLayer()
    {
        $response = $this->client->call($this->getUrl(self::ZED_CONTROLLER_PERSISTENCE_ACTION), $this->customerTransfer, [], true);

        $this->setResponse($response);
    }

}
