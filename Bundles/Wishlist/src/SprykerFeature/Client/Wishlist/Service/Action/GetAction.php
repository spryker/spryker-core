<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerFeature\Client\Wishlist\Service\Action;


use Generated\Shared\Wishlist\WishlistChangeInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

class GetAction extends AbstractActionFactory
{

    /**
     * @param TransferInterface $transfer
     *
     * @return GetAction
     */
    public function setTransferObject(TransferInterface $transfer)
    {
        return $this;
    }

    protected function handleSession()
    {
        $response = $this->session->get(self::$wishlistSessionID);

        $this->setResponse($response);
    }

    protected function handleZed()
    {
        $response = $this->client->call($this->getUrl('get-wishlist'), $this->customerTransfer, [], true);

        $this->setResponse($response);
    }

}
