<?php

namespace SprykerFeature\Zed\Cart\Business\Model;

use SprykerFeature\Shared\Cart\Transfer\CartChange;
use SprykerFeature\Shared\Cart\Transfer\CartItem;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Shared\Sales\Transfer\OrderItemCollection;
use SprykerFeature\Zed\Library\Business\ComponentModelResult;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * @deprecated
 */
class Cart
{

    const CART_TYPE_ACTION_ADD = 1;
    const CART_TYPE_ACTION_CHANGE = 2;
    const CART_TYPE_ACTION_REMOVE = 3;

    /**
     * @var CartStorage
     */
    protected $cartStorage;

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     * @param CartStorage $cartStorage
     */
    public function __construct(LocatorLocatorInterface $locator, CartStorage $cartStorage = null)
    {
        $this->locator = $locator;
        $this->cartStorage = $cartStorage;
    }

    /**
     * @param CartChange $cart
     * @param string $cartStorageAction
     * @return \SprykerFeature\Zed\Library\Business\ComponentModelResult
     */
    public function addItems(CartChange $cart, $cartStorageAction = CartStorage::CART_STORAGE_SYNCHRONIZE)
    {
        $cart = clone $cart;
        $result = $this->canPerformCartChange($cart);

        if ($result->isSuccess()) {
            $this->doCartChange(self::CART_TYPE_ACTION_ADD, $cart);
        }


        $result->setTransfer($this->getRecalculatedOrder($cart, $cartStorageAction));

        return $result;
    }

    /**
     * @param CartChange $cart
     * @return \SprykerFeature\Zed\Library\Business\ComponentModelResult
     */
    public function removeItems(CartChange $cart)
    {
        $cart = clone $cart;
        $this->doCartChange(self::CART_TYPE_ACTION_REMOVE, $cart);

        $result = new ComponentModelResult();
        $result->setTransfer($this->getRecalculatedOrder($cart));

        return $result;
    }

    /**
     * @param CartChange $cart
     * @return \SprykerFeature\Zed\Library\Business\ComponentModelResult
     */
    public function changeQuantity(CartChange $cart)
    {
        $cart = clone $cart;
        $result = $this->canPerformCartChange($cart);

        if ($result->isSuccess()) {
            $this->doCartChange(self::CART_TYPE_ACTION_CHANGE, $cart);
        }

        $result->setTransfer($this->getRecalculatedOrder($cart));

        return $result;
    }

    /**
     * @param int $changeType
     * @param CartChange $cart
     */
    public function doCartChange($changeType, CartChange $cart)
    {
        $itemCollection = $this->updateItemCollection($cart, $changeType);
        $cart->getOrder()->setItems($itemCollection);
    }

    /**
     * @param CartChange $cart
     * @param string $cartStorageAction
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function getRecalculatedOrder(CartChange $cart, $cartStorageAction = CartStorage::CART_STORAGE_SYNCHRONIZE)
    {
        // it there are no items left in the cart, the coupon code should be removed
        if ($cart->getOrder()->getItems()->count() == 0) {
            $cart->getOrder()->setCouponCodes([]);
        }

        $recalculatedOrder = $this->locator->calculation()->facade()->recalculate($cart->getOrder());

        if ($this->cartStorage) {
            $this->cartStorage->handleCartStorage($cart->getOrder(), $cart, $cartStorageAction);
        }

        return $recalculatedOrder;
    }

    /**
     * @param CartChange $cart
     * @param $actionType
     * @return \SprykerFeature\Shared\Sales\Transfer\OrderItem[]|\SprykerFeature\Shared\Sales\Transfer\OrderItemCollection
     * @throws \SprykerFeature_Zed_Library_Exception
     */
    protected function updateItemCollection(CartChange $cart, $actionType)
    {
//        if (!$this->facadeCatalog instanceof CatalogFeatureInterface) {
//            throw new \SprykerFeature_Zed_Library_Exception('Your catalog facade must implement \SprykerFeature\Zed\Calculation\Business\Model\CatalogFeatureInterface');
//        }

        $orderItems = $cart->getOrder()->getItems();
        foreach ($cart->getChangedCartItems() as $cartItem) {
            switch ($actionType) {
                case self::CART_TYPE_ACTION_ADD:
                    $this->mergeCartItemIntoOrderItems($orderItems, $cartItem);
                    break;
                case self::CART_TYPE_ACTION_CHANGE:
                    $this->changeCartItemInOrderItems($orderItems, $cartItem);
                    break;
                case self::CART_TYPE_ACTION_REMOVE:
                    $this->removeCartItemFromOrderItems($orderItems, $cartItem);
                    break;
            }
        }

        return $orderItems;
    }

    /**
     * @param OrderItemCollection $orderItems
     * @param CartItem $cartItem
     * @return OrderItemCollection
     */
    protected function mergeCartItemIntoOrderItems(OrderItemCollection $orderItems, CartItem $cartItem)
    {
        $quantity = $cartItem->getQuantity();

        $this->changeQuantityForCartItem($orderItems, $cartItem, $quantity);
    }

    /**
     * @param  OrderItemCollection $orderItems
     * @param  CartItem $cartItem
     * @throws \InvalidArgumentException
     */
    public function changeCartItemInOrderItems(OrderItemCollection $orderItems, CartItem $cartItem)
    {
        $this->changeQuantityForCartItem($orderItems, $cartItem, $cartItem->getQuantity());
    }

    /**
     * @param OrderItemCollection $orderItems
     * @param CartItem $cartItem
     */
    protected function removeCartItemFromOrderItems(OrderItemCollection $orderItems, CartItem $cartItem)
    {
        $this->changeQuantityForCartItem($orderItems, $cartItem, 0);
    }

    /**
     * @param OrderItemCollection $orderItems
     * @param CartItem $cartItem
     * @return int
     */
    protected function calculateAbsoluteQuantityForRelativeCartChange(
        OrderItemCollection $orderItems,
        CartItem $cartItem
    ) {
        $quantity = $cartItem->getQuantity();
        $sku = $cartItem->getSku();
        /* @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            if ($orderItem->getSku() === $sku) {
                $quantity++;
            }
        }

        return $this->getAvailableItemQuantity($sku, $quantity);
    }

    /**
     * @param OrderItemCollection $orderItems
     * @param CartItem $cartItem
     * @param int $quantity
     * @throws \InvalidArgumentException
     */
    protected function changeQuantityForCartItem(OrderItemCollection $orderItems, CartItem $cartItem, $quantity)
    {
        $newOrderItem = $this->cartItemToSalesItem($cartItem);
        $uniqueIdentifier = $newOrderItem->getUniqueIdentifier();
        $count = 0;

        /* @var $orderItem \SprykerFeature\Shared\Sales\Transfer\OrderItem */
        foreach ($orderItems as $key => $orderItem) {
            if ($orderItem->getUniqueIdentifier() === $uniqueIdentifier) {
                $count++;
            }
            if ($count > $quantity) {
                // If we lowered the qty remove all items which are to much
                unset($orderItems[$key]);
                $count--;
            }
        }

        if ($quantity < 1) {
            return;
        }

        $diff = $quantity - $count;
        $orderItemArray = $newOrderItem->toArray();
        for ($i = 0; $i < $diff; $i++) {
            $salesOrderItemTransfer = new \Generated\Shared\Transfer\SalesOrderItemTransfer();
            $salesOrderItemTransfer->fromArray($orderItemArray);
            $orderItems->add($salesOrderItemTransfer);
        }
    }

    /**
     * @param  string $sku
     * @param  int    $quantity
     * @return int
     */
    public function getAvailableItemQuantity($sku, $quantity)
    {

        $quantityOnStock = $this->locator
            ->availabilityCartConnector()
            ->pluginCheckAvailabilityPlugin()
            ->calculateStockForProduct($sku, $quantity);

        if ($quantity > $quantityOnStock) {
            $quantity = $quantityOnStock;
        }

        return $quantity;
    }

    /**
     * @param CartChange $cart
     * @return array
     */
    protected function areProductsOnStock(CartChange $cart)
    {
        $result = array();
        /* @var CartItem $item */
        foreach ($cart->getChangedCartItems() as $item) {
            $isSellable = $this->locator->availabilityCartConnector()->pluginCheckAvailabilityPlugin()->isProductSellable($item->getSku(), $item->getQuantity());
            $result[$item->getSku()] = $isSellable;
        }

        return $result;
    }

    /**
     * @param CartChange $cart
     * @return bool
     */
    protected function canLoadAllProducts(CartChange $cart)
    {
        /* @var CartItem $item */
        foreach ($cart->getChangedCartItems() as $item) {
            if (!$this->locator->catalog()->facade()->canLoadProductBySku($item->getSku())) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param CartChange $cart
     * @return bool
     */
    protected function canProductsHaveDefinedOptions(CartChange $cart)
    {
        /* @var CartItem $item */
        foreach ($cart->getChangedCartItems() as $item) {
            foreach ($item->getOptions() as $optionIdentifier) {
                if (!$this->locator->catalog()->facade()->canProductHaveOption($item->getSku(), $optionIdentifier)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param CartItem $cartItem
     * @return \SprykerFeature\Shared\Sales\Transfer\OrderItem
     */
    public function cartItemToSalesItem(CartItem $cartItem)
    {
        /** @var AutoCompletion $locator */
        $locator = $this->locator;
        $item = new \Generated\Shared\Transfer\SalesOrderItemTransfer();

        $sku = $cartItem->getSku();

        // @TODO: Dirty Hack for Demoshop
        /* @var $productQueryContainer ProductQueryContainerInterface */
        $localeString = \SprykerFeature_Shared_Library_Store::getInstance()->getCurrentLocale();
        $locale = $locator->locale()->facade()->getLocaleIdentifier($localeString);
        $productQueryContainer = $locator->product()->queryContainer();
        $query = $productQueryContainer->getProductWithAttributeQuery($sku, $locale);
        $query->setFormatter(new PropelArraySetFormatter());
        $product = $query->findOne();
        $attributes = json_decode($product['attributes'], true);

        $pricePlugin = $this->locator->priceCartConnector()->pluginGetPricePlugin();
        $price = $pricePlugin->getPrice($sku, null, null);
//        $pricePlugin->ge

//        $name = $this->facadeCatalog->getProductNameBySku($sku);
//        $price = $this->facadeCatalog->getProductPriceBySku($sku);
//        $taxRate = $this->facadeCatalog->getProductTaxRateBySku($sku);
//        $variety = $this->facadeCatalog->getProductVarietyBySku($sku);
        $name = $product['name'];
//        $price = $attributes['price'];
        $taxRate = 19.0;
        $variety = 'Single';
        //@TODO: hack

        $item->setName($name);
        $item->setTaxPercentage($taxRate);
        $item->setGrossPrice($price);
        $item->setSku($sku);
        $item->setQuantity(1);
        $item->setVariety($variety);

        foreach ($cartItem->getOptions() as $optionIdentifier) {
            $item->addOption($this->createOptionTransfer($optionIdentifier));
        }
        $this->locator->sales()->facade()->addUniqueIdentifierForItem($item);

        return $item;
    }

    /**
     * @param string $optionIdentifier
     * @return \SprykerFeature\Shared\Sales\Transfer\OrderItemOption
     */
    protected function createOptionTransfer($optionIdentifier)
    {
        $optionEntity = $this->facadeCatalog->getProductOptionByIdentifier($optionIdentifier);
        $salesOption = new \Generated\Shared\Transfer\SalesOrderItemOptionTransfer();
        $salesOption->setIdentifier($optionIdentifier);
        $salesOption->setName($optionEntity->getName());
        $salesOption->setDescription($optionEntity->getDescription());
        $salesOption->setType($optionEntity->getOptionType()->getName());
        $salesOption->setGrossPrice($optionEntity->getPrice());
        $salesOption->setTaxPercentage($optionEntity->getTaxPercentage());

        return $salesOption;
    }

    /**
     * Filter all stored and maybe no longer available items from stored items.
     * To filter items by quantity add quantity of item in cart to quantity of stored item.
     * @param ObjectCollection $customerItems
     * @param OrderItemCollection $sessionItems
     * @return ObjectCollection
     */
    public function filterCustomerItems(ObjectCollection $customerItems, OrderItemCollection $sessionItems)
    {
        $filteredItems = new ObjectCollection();
        $customerItems = $this->addQuantityOfCartItemsToStoredItems($customerItems, $sessionItems);
        /* @var $customerItem \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem */
        foreach ($customerItems as $customerItem) {
            $transferCartChange = new \Generated\Shared\Transfer\CartChangeTransfer();
            $transferCartItem = new \Generated\Shared\Transfer\CartItemTransfer();
            $transferCartItem = Copy::entityToTransfer($transferCartItem, $customerItem);
            $transferCartChange->getChangedCartItems()->add($transferCartItem);
            if ($this->canPerformCartChange($transferCartChange)->isSuccess()) {
                $filteredItems->append($customerItem);
            }
        }

        return $filteredItems;
    }

    /**
     * @param ObjectCollection $customerItems
     * @param OrderItemCollection $sessionItems
     * @return ObjectCollection
     */
    protected function addQuantityOfCartItemsToStoredItems(
        ObjectCollection $customerItems,
        OrderItemCollection $sessionItems
    ) {
        /* @var $customerItem \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem */
        foreach ($customerItems as $customerItem) {
            /* @var $sessionItem \SprykerFeature\Shared\Sales\Transfer\OrderItem */
            foreach ($sessionItems as $sessionItem) {
                if ($sessionItem->getSku() === $customerItem->getSku()) {
                    $customerItem->setQuantity($customerItem->getQuantity() + $sessionItem->getQuantity());
                }
            }
        }

        return $customerItems;
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Zed\Library\Business\ComponentModelResult
     */
    public function canPerformCartChange(CartChange $cartChange)
    {
        $result = new ComponentModelResult();
//        if (!$this->canLoadAllProducts($cartChange)) {
//            $result->addError(\SprykerFeature_Shared_Cart_Code_Messages::ERROR_LOAD_PRODUCT);
//
//            return $result;
//        }
//
//        if (!$this->canProductsHaveDefinedOptions($cartChange)) {
//            $result->addError(\SprykerFeature_Shared_Cart_Code_Messages::ERROR_INVALID_OPTION_SPECIFIED);
//
//            return $result;
//        }
//
//        if (!$this->areProductsOnStock($cartChange)) {
//            $result->addError(\SprykerFeature_Shared_Cart_Code_Messages::ERROR_PRODUCT_QUANTITY_CHANGE);
//
//            return $result;
//        }

        return $result;
    }
}
