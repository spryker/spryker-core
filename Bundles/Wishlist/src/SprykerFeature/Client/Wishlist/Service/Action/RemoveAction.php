<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerFeature\Client\Wishlist\Service\Action;


use Generated\Shared\Wishlist\WishlistInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

class RemoveAction extends AbstractActionFactory
{

    public function setTransferObject(TransferInterface $transfer)
    {
        if (!$transfer instanceof WishlistInterface) {
            throw new \InvalidArgumentException( printf("Wishlist Remove Action should get WishlistInterface transfer object"));
        }

        $this->transferObject = $transfer;

        return $this;
    }

    protected function handleSession()
    {
        if(!$this->transferObject instanceof TransferInterface) {
            throw new \RuntimeException("Transfer Object should be set in oder to use Wishlist Remove action");
        }

        $this->session->set(self::WISHLIST_SESSION_IDENTIFIER, $this->transferObject);
    }

    protected function handleZed()
    {
        $this->client->call($this->getUrl('remove'), $this->transferObject);
    }

}
