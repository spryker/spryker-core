<?php

namespace SprykerFeature\Zed\Cart\Business\Model;

use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Shared\Sales\Transfer\OrderItemCollection;
use SprykerFeature\Shared\Cart\Code\DeleteReasonConstant;
use SprykerFeature\Shared\Cart\Transfer\CartChange;
use SprykerEngine\Zed\Kernel\Locator;

class CartStorage
{

    const CART_STORAGE_SYNCHRONIZE = 'cart_storage_syncronize';
    const CART_STORAGE_MERGE = 'cart_storage_merge';

    /**
     * @var \SprykerFeature_Zed_Cart_Business_Model_Strategies_MergeStrategyInterface
     */
    protected $mergeStrategy;

    /**
     * @var \SprykerFeature_Zed_Cart_Business_Model_Strategies_ClearStrategyInterface
     */
    protected $clearStrategy;

    /**
     * @var bool
     */
    protected $cartStorageEnabled;

    /**
     * @param CartChange $cart
     * @return Order
     */
    public function mergeGuestCartWithCustomerCart(CartChange $cart)
    {
        if (!$this->cartStorageEnabled) {
            return $cart->getOrder();
        }
        $customerId = $cart->getUserId();
        $customerItems = $this->getCustomerItems($customerId);
        $sessionItems = $cart->getOrder()->getItems();
        $customerItems = $this->factory->createModelCart()->filterCustomerItems($customerItems, $sessionItems);

        /* @var $item \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem */
        foreach ($customerItems as $item) {
            $sessionItems = $this->mergeItemWithCartCollection($sessionItems, $cart, $item);
        }
        $cart->getOrder()->setItems($sessionItems);
        $recalculatedOrder = $this->factory->createModelCart()->addItems($cart, self::CART_STORAGE_MERGE);

        return $recalculatedOrder;
    }

    /**
     * @param CartChange $cart
     * @return Order
     */
    public function loadGuestCartByHash(CartChange $cart)
    {
        $hash = $cart->getCartHash();
        if (!$this->cartStorageEnabled || empty($hash)) {
            return $cart->getOrder();
        }

        $guestItems = $this->getGuestItems($hash);
        $sessionItems = $cart->getOrder()->getItems();

        /* @var $item \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem */
        foreach ($guestItems as $item) {
            $sessionItems = $this->mergeItemWithCartCollection($sessionItems, $cart, $item);
        }
        $cart->getOrder()->setItems($sessionItems);
        $recalculatedOrder = $this->factory->createModelCart()->addItems($cart);

        return $recalculatedOrder;
    }

    /**
     * @param CartChange $cart
     * @return Order
     */
    public function clearCartStorage(CartChange $cart)
    {
        if (!$this->cartStorageEnabled) {
            return $cart->getOrder();
        }
        $cartStorage = $this->findCartStorage($cart);
        if ($cartStorage) {
            $this->getClearStrategy()->clearCartStorage($cartStorage);
        }

        return $cart->getOrder();
    }

    /**
     * @param Order $order
     * @param CartChange $cart
     * @param string $cartStorageAction
     */
    public function handleCartStorage(Order $order, CartChange $cart, $cartStorageAction)
    {
        if (true !== $this->cartStorageEnabled) {
            return;
        }
        switch ($cartStorageAction) {
            case self::CART_STORAGE_SYNCHRONIZE:
                $this->synchronizeCartStorage($order, $cart);
                break;
            case self::CART_STORAGE_MERGE:
                $this->mergeCartStorage($order, $cart);
                break;
        }
    }

    /**
     * @return \SprykerFeature_Zed_Cart_Business_Model_Strategies_MergeStrategyInterface
     */
    protected function getMergeStrategy()
    {
        if (empty($this->mergeStrategy)) {
            $settings = $this->factory->createSettings();
            $this->mergeStrategy = $settings->getCartStorageMergeStrategy();
            assert($this->mergeStrategy instanceof \SprykerFeature_Zed_Cart_Business_Model_Strategies_MergeStrategyInterface);
        }

        return $this->mergeStrategy;
    }

    /**
     * @return \SprykerFeature_Zed_Cart_Business_Model_Strategies_ClearStrategyInterface
     */
    protected function getClearStrategy()
    {
        if (empty($this->clearStrategy)) {
            $settings = $this->factory->createSettings();
            $this->clearStrategy = $settings->getCartStorageClearStrategy();
            assert($this->clearStrategy instanceof \SprykerFeature_Zed_Cart_Business_Model_Strategies_ClearStrategyInterface);
        }

        return $this->clearStrategy;
    }

    /**
     * @param OrderItemCollection $saleItems
     * @param CartChange $cart
     * @param \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $itemToAdd
     * @return OrderItemCollection
     */
    protected function mergeItemWithCartCollection(OrderItemCollection $saleItems, CartChange $cart, \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $itemToAdd)
    {
        $tmpSaleItem = Locator::getInstance()->sales()->transferOrderItem();
        $oldQuantity = 0;
        /* @var $saleItem OrderItem */
        foreach ($saleItems as $saleItem) {
            if ($saleItem->getUniqueIdentifier() === $itemToAdd->getUniqueIdentifier()) {
                $tmpSaleItem->fromArray($saleItem->toArray(false));
                $oldQuantity++;
            }
        }

        // We already have something in cart
        if ($oldQuantity > 0) {
            $tmpSaleItem->setQuantity($oldQuantity);
            $newQuantity = $this->getMergeStrategy()->getQuantity($tmpSaleItem, $itemToAdd);

            $cartItem = Locator::getInstance()->cart()->transferItem();
            $cartItem->setSku($saleItem->getSku());
            $cartItem->setUniqueIdentifier($saleItem->getUniqueIdentifier());
            $cartItem->setQuantity($newQuantity);
            $this->factory->createModelCart()->changeCartItemInOrderItems($saleItems, $cartItem);

            return $saleItems;
        }

        $newTransfer = Locator::getInstance()->cart()->transferItem();
        $newTransfer->setSku($itemToAdd->getSku());
        $newTransfer->setQuantity($itemToAdd->getQuantity());
        $optionArray = [];
        foreach ($itemToAdd->getOptions() as $option) {
            $optionArray[] = $option->getIdentifier();
        }
        $newTransfer->setOptions($optionArray);
        $cart->getChangedCartItems()->add($newTransfer);

        return $saleItems;
    }

    /**
     * @param Order $order
     * @param CartChange $cart
     */
    protected function synchronizeCartStorage(Order $order, CartChange $cart)
    {
        $cartStorage = $this->getOrCreateCartStorage($cart);
        $availableCartItems = $this->getCartItemsForCartChangeTransfer($cart);
        $this->synchronizeCartStorageItems($cartStorage, $availableCartItems, $order->getItems(), $cart);
    }

    /**
     * @param Order $order
     * @param CartChange $cart
     */
    protected function mergeCartStorage(Order $order, CartChange $cart)
    {
        $customerId = $cart->getUserId() > 0 ? $cart->getUserId() : null;
        $cartStorage = $this->getMergedCustomerCartStorage($customerId, $cart->getCartHash());
        $customerCartItems = $cartStorage->getCartItems();
        $this->synchronizeCartStorageItems($cartStorage, $customerCartItems, $order->getItems(), $cart);
    }

    /**
     * @param $customerId
     * @param $cartHash
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart
     */
    protected function getMergedCustomerCartStorage($customerId, $cartHash)
    {
        $cartStorage = $this->getCustomerCartStorage($customerId);
        if ($cartStorage) {
            //have to move guest items to existing customer cart
            $guestCart = $this->getGuestCartStorage($cartHash);
            if ($guestCart) {
                $this->moveGuestCartItemsToCustomerCart($cartStorage, $guestCart);
            }
        } else {
            //create a cart user for guest cart
            $cartStorage = $this->getGuestCartStorage($cartHash);
            if (!$cartStorage) {
                $cartStorage = $this->createCart($cartHash);
            }
            $this->createCartUser($cartStorage, $customerId);
        }

        return $cartStorage;
    }

    /**
     * @param \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $cartStorage
     * @param PropelObjectCollection $cartItems
     * @param OrderItemCollection $orderItems
     * @param CartChange $cart
     */
    protected function synchronizeCartStorageItems(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $cartStorage, PropelObjectCollection $cartItems, OrderItemCollection $orderItems, CartChange $cart)
    {
        $groupedOrderItems = $this->groupOrderItemsByUniqueIdentifier($orderItems);
        /* @var $availableItem \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem */
        foreach ($cartItems as $idx => $availableItem) {
            $uniqueIdentifier = $availableItem->getUniqueIdentifier();
            if (isset($groupedOrderItems[$uniqueIdentifier])) {
                $orderQty = $groupedOrderItems[$uniqueIdentifier]['quantity'];
                $availableItem->setQuantity($orderQty);
                if ($availableItem->isModified()) {
                    $availableItem->save();
                }
                //remove from available and incoming items
                if ($cartItems->offsetExists($idx)) {
                    $cartItems->remove($idx);
                }
                unset($groupedOrderItems[$uniqueIdentifier]);
            }
        }
        //store rest of incoming items as new cart items
        foreach ($groupedOrderItems as $uniqueIdentifier => $data) {
            $entity = $this->createCartItem($cartStorage->getPrimaryKey(), $data['sku'], $uniqueIdentifier, $data['quantity']);
            if (!empty($data['options'])) {
                foreach ($data['options'] as $optionIdentifier) {
                    $this->createCartItemOption($entity->getIdCartItem(), $optionIdentifier);
                }
            }
        }
        //delete rest of available items
        $this->deleteCartItems($cartItems, $cart->getDeleteReason());
    }

    /**
     * @param \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $cartItem
     * @param int                                       $deleteCause
     */
    protected function deleteCartItem(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $cartItem, $deleteCause = DeleteReasonConstant::DELETE_REASON_CAUSE_UNDEFINED)
    {
        $this->getClearStrategy()->clearCartItem($cartItem, $deleteCause);
    }

    /**
     * @param PropelObjectCollection $cartItemCollection
     * @param int                    $deleteCause
     */
    protected function deleteCartItems(PropelObjectCollection $cartItemCollection, $deleteCause = DeleteReasonConstant::DELETE_REASON_CAUSE_UNDEFINED)
    {
        $this->getClearStrategy()->clearCartItems($cartItemCollection, $deleteCause);
    }

    /**
     * @param  int                                       $cartId
     * @param  string                                    $sku
     * @param  string                                    $uniqueIdentifier
     * @param  int                                       $quantity
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem
     */
    protected function createCartItem($cartId, $sku, $uniqueIdentifier, $quantity)
    {
        $entity = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem();
        $entity->setQuantity($quantity);
        $entity->setSku($sku);
        $entity->setUniqueIdentifier($uniqueIdentifier);
        $entity->setFkCart($cartId);
        $entity->save();

        return $entity;
    }

    /**
     * @param  int                                             $cartItemId
     * @param  string                                          $optionIdentifier
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItemOption
     */
    protected function createCartItemOption($cartItemId, $optionIdentifier)
    {
        $entity = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItemOption();
        $entity->setIdentifier($optionIdentifier);
        $entity->setFkCartItem($cartItemId);
        $entity->save();

        return $entity;
    }

    /**
     * @param $cartHash
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart
     */
    protected function createCart($cartHash)
    {
        $entity = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart();
        $entity->setCartHash($cartHash);
        $entity->save();

        return $entity;
    }

    /**
     * @param  \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $customerCart
     * @param  \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $guestCart
     * @return int
     */
    protected function moveGuestCartItemsToCustomerCart(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $customerCart, \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $guestCart)
    {
        $cartItemQuery = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItemQuery();
        $updatedAmount = $cartItemQuery->filterByCart($guestCart)
            ->filterByIsDeleted(false)
            ->update(array('FkCart' => $customerCart->getPrimaryKey()));

        return $updatedAmount;
    }

    /**
     * Get customer or guest cart depending if user is logged in or not
     * @param  CartChange                            $cart
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart
     */
    protected function getOrCreateCartStorage(CartChange $cart)
    {
        $hash = $this->ensureCartHash($cart);
        if ($this->isUserLoggedIn($cart)) {
            $cartStorage = $this->getCustomerCartStorage($cart->getUserId());
            if (!$cartStorage) {
                $cartStorage = $this->createCart($hash);
                $this->createCartUser($cartStorage, $cart->getUserId());
            }
        } else {
            $cartStorage = $this->getGuestCartStorage($hash);
            if (!$cartStorage) {
                $cartStorage = $this->createCart($hash);
            }
        }

        return $cartStorage;
    }

    /**
     * @param  CartChange $cart
     * @return string
     */
    protected function ensureCartHash(CartChange $cart)
    {
        $hash = $cart->getCartHash();
        if (empty($hash)) {
            $hash = $this->generateCartHash();
            $cart->setCartHash($hash);
        }

        return $hash;
    }

    /**
     * @return string
     */
    protected function generateCartHash()
    {
        return md5(microtime() . rand());
    }

    /**
     * Get customer or guest cart depending if user is logged in or not
     * @param  CartChange                                 $cart
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart|null
     */
    protected function findCartStorage(CartChange $cart)
    {
        if ($this->isUserLoggedIn($cart)) {
            $cartStorage = $this->getCustomerCartStorage($cart->getUserId());
        } else {
            $cartStorage = $this->getGuestCartStorage($cart->getCartHash());
        }

        return $cartStorage;
    }

    /**
     * @param $cartHash
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart
     */
    protected function getGuestCartStorage($cartHash)
    {
        $query = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartQuery();

        return $query->findOneByCartHash($cartHash);
    }

    /**
     * @param $customerId
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart
     */
    protected function getCustomerCartStorage($customerId)
    {
        return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartQuery::create()
            ->useCartUserQuery()
                ->filterByFkCustomer($customerId)
            ->endUse()
            ->findOne();
    }

    /**
     * @param \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $cart
     * @param $customerId
     * @return \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartUser
     */
    protected function createCartUser(\SprykerFeature\Zed\Cart\Persistence\Propel\SpyCart $cart, $customerId)
    {
        $entity = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartUser();
        $entity->setCart($cart);
        $entity->setFkCustomer($customerId);
        $entity->save();

        return $entity;
    }

    /**
     * @param  CartChange             $cart
     * @return PropelObjectCollection
     */
    protected function getCartItemsForCartChangeTransfer(CartChange $cart)
    {
        if ($this->isUserLoggedIn($cart)) {
            return $this->getCustomerItems($cart->getUserId());

        } else {
            return $this->getGuestItems($cart->getCartHash());
        }
    }

    /**
     * @param CartChange $cart
     * @return bool
     */
    protected function isUserLoggedIn(CartChange $cart)
    {
        return ($cart->getUserId() > 0);
    }

    /**
     * @param $cartHash
     * @return PropelObjectCollection
     */
    protected function getGuestItems($cartHash)
    {
        $query = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItemQuery();

        return $query->filterByIsDeleted(false)
            ->useCartQuery()
            ->where(\SprykerFeature\Zed\Cart\Persistence\Propel\Map\SpyCartTableMap::COL_CART_HASH . ' = ?', $cartHash)
            ->endUse()
            ->find();
    }

    /**
     * @param int $customerId
     * @return PropelObjectCollection
     */
    protected function getCustomerItems($customerId)
    {
        $query = new \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItemQuery();

        return $query->filterByIsDeleted(false)
            ->useCartQuery()
                ->useCartUserQuery()
                    ->where(\SprykerFeature\Zed\Cart\Persistence\Propel\Map\SpyCartUserTableMap::COL_FK_CUSTOMER . ' = ?', $customerId)
                ->endUse()
            ->endUse()
            ->find();
    }

    /**
     * @param  OrderItemCollection $orderItems
     * @return array
     */
    protected function groupOrderItemsByUniqueIdentifier(OrderItemCollection $orderItems)
    {
        $groupedItems = array();
        /* @var $orderItem SprykerFeature\Shared\Sales\Transfer\OrderItem */
        foreach ($orderItems as $orderItem) {
            $uniqueIdentifier = $orderItem->getUniqueIdentifier();
            $groupedItems[$uniqueIdentifier]['sku'] = $orderItem->getSku();
            if (!isset($groupedItems[$uniqueIdentifier]['quantity'])) {
                $groupedItems[$uniqueIdentifier]['quantity'] = 0;
            }
            $groupedItems[$uniqueIdentifier]['quantity'] += 1;
            /* @var SprykerFeature\Shared\Sales\Transfer\OrderItemOption $option */
            foreach ($orderItem->getOptions() as $option) {
                $groupedItems[$uniqueIdentifier]['options'][] = $option->getIdentifier();
            }
        }

        return $groupedItems;
    }
}
