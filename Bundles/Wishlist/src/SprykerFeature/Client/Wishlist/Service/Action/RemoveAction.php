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

    const ZED_CONTROLLER_FILTER_ACTION = 'ungroup';

    const ZED_CONTROLLER_PERSISTENCE_ACTION = 'remove';

    /**
     * @var WishlistChangeInterface
     */
    private $changeTransfer;

    /**
     * @param TransferInterface $transfer
     *
     * @return RemoveAction
     */
    public function setTransferObject(TransferInterface $transfer)
    {
        if (!$transfer instanceof WishlistItemInterface) {
            throw new \InvalidArgumentException('WishlistItem Remove Action needs WishlistItemInterface argument');
        }

        $this->changeTransfer = (new WishlistChangeTransfer())
            ->setRemovedItems(new \ArrayObject([$transfer]));

        return $this;
    }

    protected function synchronizeSessionLayer()
    {
        if (!$this->changeTransfer instanceof WishlistChangeInterface) {
            throw new \InvalidArgumentException('Wishlist Remove Action should get WishlistChangeInterface transfer object for handling a remove routine on Zed side');
        }

        if(null === $sessionItems = $this->session->get(self::getWishlistSessionID())) {
            return;
        }

        $this->changeTransfer->setItems($sessionItems->getItems());

        $wishlist = $this->client->call($this->getUrl(self::ZED_CONTROLLER_FILTER_ACTION), $this->changeTransfer, null, true);

        $this->session->set(self::getWishlistSessionID(), $wishlist);
    }

    protected function synchronizePersistingLayer()
    {
        $this->client->call($this->getUrl(self::ZED_CONTROLLER_PERSISTENCE_ACTION), $this->changeTransfer);
    }

}
