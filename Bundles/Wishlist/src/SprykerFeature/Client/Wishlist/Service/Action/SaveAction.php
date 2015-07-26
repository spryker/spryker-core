<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerFeature\Client\Wishlist\Service\Action;


use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

class SaveAction extends AbstractActionFactory
{

    /**
     * @param TransferInterface $transfer
     *
     * @throws \InvalidArgumentException
     *
     * @return SaveAction
     */
    public function setTransferObject(TransferInterface $transfer)
    {
        if (!$transfer instanceof WishlistChangeInterface) {
            throw new \InvalidArgumentException( printf("Save Action should get "));
        }

        $this->transferObject = $transfer;

        return $this;
    }

    protected function handleSession()
    {
        if(!$this->transferObject instanceof TransferInterface) {
            throw new \RuntimeException("Transfer Object should be set in oder to use Wishlist Save action");
        }



        $this->session->set(self::WISHLIST_SESSION_IDENTIFIER, $this->transferObject);
    }

    protected function handleZed()
    {
        $this->client->call($this->getUrl('save'), $this->transferObject);
    }

    private function extendWishlistItemTransfer(WishlistInterface $wishlistInterface, WishlistChangeInterface $wishlistChangeTransfer)
    {

    }

}
