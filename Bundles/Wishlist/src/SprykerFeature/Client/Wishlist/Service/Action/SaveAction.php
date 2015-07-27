<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerFeature\Client\Wishlist\Service\Action;


use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

class SaveAction extends AbstractActionFactory
{
    const ZED_CONTROLLER_FILTER_ACTION = 'group';

    const ZED_CONTROLLER_PERSISTENCE_ACTION = 'store';

    /**
     * @var WishlistChangeInterface
     */
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
            throw new \InvalidArgumentException('Save Action should get');
        }

        $this->changeTransfer = (new WishlistChangeTransfer())
            ->setAddedItems(new \ArrayObject([$transfer]));

        return $this;
    }

    protected function synchronizeSessionLayer()
    {
        if(!$this->changeTransfer instanceof WishlistChangeInterface) {
            throw new \RuntimeException('Transfer Object should be set and implement WishlistChangeInterface, in oder to use Wishlist Save action');
        }

        if(null !== $sessionItems = $this->session->get(self::getWishlistSessionID())) {

            $this->changeTransfer->setItems($sessionItems->getItems());

        }

        $wishlistItems = $this->client->call($this->getUrl(self::ZED_CONTROLLER_FILTER_ACTION), $this->changeTransfer, null, true);

        $this->session->set(self::getWishlistSessionID(), $wishlistItems);
    }

    protected function synchronizePersistingLayer()
    {
        $this->changeTransfer->setCustomer($this->customerTransfer);

        $this->client->call($this->getUrl(self::ZED_CONTROLLER_PERSISTENCE_ACTION), $this->changeTransfer, null, true);
    }


}
