<?php

namespace SprykerFeature\Sdk\Cart\Model;

use Generated\Sdk\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerEngine\Yves\Kernel\Locator;
use SprykerFeature\Sdk\Cart\StorageProvider\StorageProviderInterface;
use SprykerFeature\Sdk\ZedRequest\Client\ZedClient;
use SprykerFeature\Shared\Cart\Transfer\CartChangeInterface;
use SprykerFeature\Shared\Cart\Transfer\CartInterface as CartTransferInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemInterface;

class Cart implements CartInterface
{
    /**
     * @var ZedClient
     */
    private $zedClient;
    /**
     * @var StorageProviderInterface
     */
    private $storageProvider;

    /**
     * @var AbstractLocatorLocator|AutoCompletion
     */
    private $locator;

    /**
     * @param ZedClient $zedClient
     * @param StorageProviderInterface $storageProvider
     */
    public function __construct(ZedClient $zedClient, StorageProviderInterface $storageProvider)
    {

        $this->zedClient = $zedClient;
        $this->storageProvider = $storageProvider;
        $this->locator = Locator::getInstance();
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartTransferInterface
     */
    public function addToCart($sku, $quantity = 1)
    {
        $addedItems = $this->createChangedItems($sku, $quantity);
        $cartChange = $this->prepareCartChange($addedItems);
        $this->getZedClient()->call('/cart/add-item', $cartChange);

        return $this->handleCartResponse();
    }

    /**
     * @param string $sku
     *
     * @return CartTransferInterface
     */
    public function removeFromCart($sku)
    {
        $cart = $this->getStorageProvider()->getCart();

        if ($cart->getItems()->offsetExists($sku)) {
            $deleteItem = $cart->getItems()->offsetGet($sku);
            $deletedItems = $this->createChangedItems($sku, $deleteItem->getQuantity());
            $cartChange = $this->prepareCartChange($deletedItems);
            $this->getZedClient()->call('/cart/remove-item', $cartChange);

            return $this->handleCartResponse();
        }

        return $cart;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartTransferInterface
     */
    public function decreaseItemQuantity($sku, $quantity = 1)
    {
        $cart = $this->getStorageProvider()->getCart();

        if ($cart->getItems()->offsetExists($sku)) {
            $decreasedItems = $this->createChangedItems($sku, $quantity);
            $cartChange = $this->prepareCartChange($decreasedItems);
            $this->getZedClient()->call('/cart/decrease-item-quantity', $cartChange);

            return $this->handleCartResponse();
        }

        return $cart;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartTransferInterface
     */
    public function increaseItemQuantity($sku, $quantity = 1)
    {
        $increasedItems = $this->createChangedItems($sku, $quantity);
        $cartChange = $this->prepareCartChange($increasedItems);
        $this->getZedClient()->call('/cart/increase-item-quantity', $cartChange);

        return $this->handleCartResponse();
    }

    /**
     * @return CartTransferInterface
     */
    public function recalculate()
    {
        //@todo $this->storageProvider->getCart();
    }


    /**
     * @return StorageProviderInterface
     */
    protected function getStorageProvider()
    {
        return $this->storageProvider;
    }

    /**
     * @return ZedClient
     */
    protected function getZedClient()
    {
        return $this->zedClient;
    }

    /**
     * @return CartChangeInterface
     */
    protected function createCartChange()
    {
        $cart = $this->storageProvider->getCart();
        /** @var CartChangeInterface $cartChange */
        $cartChange = $this->locator->Cart()->transferCartChange();
        $cartChange->setCart($cart);

        return $cartChange;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return AbstractTransfer|ItemCollectionInterface
     */
    protected function createChangedItems($sku, $quantity = 1)
    {
        /** @var ItemInterface|AbstractTransfer $changedItem */
        $changedItem = $this->locator->Cart()->transferItem();
        $changedItem->setId($sku);
        $changedItem->setQuantity($quantity);
        /** @var ItemCollectionInterface|AbstractTransfer $changedItems */
        $changedItems = $this->locator->Cart()->transferItemCollection();
        $changedItems->add($changedItem);

        return $changedItems;
    }

    /**
     * @param ItemCollectionInterface $changedItems
     *
     * @return CartChangeInterface
     */
    protected function prepareCartChange(ItemCollectionInterface $changedItems)
    {
        $cartChange = $this->createCartChange();
        $cartChange->setChangedItems($changedItems);

        return $cartChange;
    }

    /**
     * @return CartTransferInterface
     */
    protected function handleCartResponse()
    {
        $cartResponse = $this->getZedClient()->getLastResponse();

        if (!$cartResponse->isSuccess()) {
            //@todo log errors

            return $this->getStorageProvider()->getCart();
        }

        /** @var CartTransferInterface $cart */
        $cart = $cartResponse->getTransfer();
        $this->getStorageProvider()->setCart($cart);

        return $cart;
    }
}
