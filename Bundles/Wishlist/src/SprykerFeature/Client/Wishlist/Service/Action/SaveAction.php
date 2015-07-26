<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerFeature\Client\Wishlist\Service\Action;


use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

class SaveAction extends AbstractActionFactory
{

    private $changeTransfer;

    /**
     * @param TransferInterface $transfer
     *
     * @throws \InvalidArgumentException
     *
     * @return SaveAction
     */
    public function setTransferObject(TransferInterface $transfer)
    {

        if (!$transfer instanceof WishlistItemInterface) {
            throw new \InvalidArgumentException( printf("Save Action should get "));
        }

        $this->changeTransfer = (new WishlistChangeTransfer())
            ->setAddedItems(new \ArrayObject($transfer));

        return $this;
    }

    protected function handleSession()
    {
        if(!$this->changeTransfer instanceof WishlistChangeInterface) {
            throw new \RuntimeException("Transfer Object should be set and implement WishlistChangeInterface, in oder to use Wishlist Save action");
        }

        if(null !== $sessionItems = $this->session->get(self::$wishlistSessionID)) {

            $this->changeTransfer->setItems($sessionItems->getItems());

        }

        $wishlistItems = $this->client->call($this->getUrl('group'), $this->changeTransfer, null, true);

        $this->session->set(self::$wishlistSessionID, $wishlistItems);
    }

    protected function handleZed()
    {
        $this->client->call($this->getUrl('store'), $this->changeTransfer);
    }


}
