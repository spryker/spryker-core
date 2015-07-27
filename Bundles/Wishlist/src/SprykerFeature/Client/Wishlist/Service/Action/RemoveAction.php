<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerFeature\Client\Wishlist\Service\Action;


use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

class RemoveAction extends AbstractActionFactory
{
    private $changeTransfer;

    /**
     * @param TransferInterface $transfer
     *
     * @return RemoveAction
     */
    public function setTransferObject(TransferInterface $transfer)
    {
        if (!$transfer instanceof WishlistItemInterface) {
            throw new \InvalidArgumentException("WishlistItem Remove Action needs WishlistItemInterface argument");
        }

        $this->changeTransfer = (new WishlistChangeTransfer())
            ->setRemovedItems(new \ArrayObject([$transfer]));

        return $this;
    }

    protected function handleSession()
    {
        if (!$this->changeTransfer instanceof WishlistChangeInterface) {
            throw new \InvalidArgumentException( printf("Wishlist Remove Action should get WishlistChangeInterface transfer object for handling a remove routine on Zed side"));
        }

        if(null === $sessionItems = $this->session->get(self::getWishlistSessionID())) {
            return;
        }

        $this->changeTransfer->setItems($sessionItems->getItems());

        $wishlist = $this->client->call($this->getUrl('ungroup'), $this->changeTransfer, null, true);

        $this->session->set(self::getWishlistSessionID(), $wishlist);
    }

    protected function handleZed()
    {
        $this->client->call($this->getUrl('remove'), $this->changeTransfer);
    }

}
