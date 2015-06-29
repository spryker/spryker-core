<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Cart\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\CartItemsTransfer;
use SprykerFeature\Shared\Cart\Code\DeleteReasonConstant;
use SprykerFeature\Shared\Library\Communication\Response;
use SprykerFeature\Shared\Sales\Code\AbstractItemGrouper;
use SprykerFeature\Shared\ZedRequest\Client\AbstractZedClient;
use SprykerFeature\Yves\Cart\CartStorage\CartStorageInterface;
use SprykerEngine\Zed\Kernel\Locator;

class ZedCart implements CartInterface
{
    /**
     * @var CartStorageInterface
     */
    protected $cartStorage;

    /**
     * @var CartSession
     */
    protected $cartSession;

    /**
     * @var CartCountInterface
     */
    protected $cartCount;

    /**
     * @var AbstractItemGrouper
     */
    protected $itemGrouper;

    /**
     * @var LocatorLocatorInterface|AutoCompletion
     */
    protected $locator;

    /**
     * @var AbstractZedClient
     */
    protected $zedClient;

    /**
     * @param CartSession $cartSession
     * @param AbstractItemGrouper $itemGrouper
     * @param LocatorLocatorInterface|AutoCompletion $locator
     * @param CartStorageInterface $cartStorage
     * @param CartCountInterface $cartCount
     */
    public function __construct(
        CartSession $cartSession,
        AbstractItemGrouper $itemGrouper,
        LocatorLocatorInterface $locator,
        CartStorageInterface $cartStorage = null,
        CartCountInterface $cartCount = null
    ) {
        $this->cartSession = $cartSession;
        $this->itemGrouper = $itemGrouper;
        $this->locator = $locator;
        $this->cartStorage = $cartStorage;
        $this->cartCount   = $cartCount;
        $this->zedClient = $locator->zedRequest()->zedClient()->getInstance();
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->cartSession->getOrder();
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->cartSession->setOrder($order);
        if ($this->cartCount) {
            $this->cartCount->setCount($this->getCount());
        }
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->getOrder()->getItems()->count();
    }

    /**
     * @param bool $groupItemsByUniqueId
     * @return OrderItem[]|OrderItemCollection
     */
    public function getItems($groupItemsByUniqueId = true)
    {
        $items = $this->getOrder()->getItems();
        if ($groupItemsByUniqueId) {
            $items = $this->itemGrouper->groupItemsByUniqueId($items);
        }
        return $items;
    }

    /**
     * @param CartItem $cartItem
     * @return Response
     */
    public function addItem(CartItem $cartItem)
    {
        $cartItemCollection  = new CartItemsTransfer();
        $cartItemCollection->addCartItem($cartItem);

        return $this->addItems($cartItemCollection);
    }

    /**
     * @param CartItem $cartItem
     * @param int $reason
     * @return Response
     */
    public function removeItem(
        CartItem $cartItem,
        $reason = DeleteReasonConstant::DELETE_REASON_ACTIVELY_REMOVED_BY_USER
    ) {
        $cartItemCollection  = new CartItemsTransfer();
        $cartItemCollection->addCartItem($cartItem);

        return $this->removeItems($cartItemCollection, $reason);
    }

    /**
     * @param CartItem $cartItem
     * @return Response
     */
    public function changeQuantityOfItem(CartItem $cartItem)
    {
        $cartItemCollection  = new CartItemsTransfer();
        $cartItemCollection->addCartItem($cartItem);

        return $this->changeQuantityOfItems($cartItemCollection);
    }

    /**
     * @param CartItem[]|CartItemCollection $cartItemCollection
     * @return Response
     * @throws \InvalidArgumentException
     */
    public function addItems($cartItemCollection)
    {
        $transferCartChange = $this->createCartChange($cartItemCollection);

        $order = $this->zedClient->call('cart/sdk/add-items', $transferCartChange);
        $this->setOrder($order);

        return $this->zedClient->getLastResponse();
    }

    /**
     * @param CartItem[]|CartItemCollection $cartItemCollection
     * @param int $reason @see \SprykerFeature_Shared_Library_Cart
     * @return Response
     * @throws \InvalidArgumentException
     */
    public function removeItems(
        $cartItemCollection,
        $reason = DeleteReasonConstant::DELETE_REASON_ACTIVELY_REMOVED_BY_USER
    ) {
        $transferCartChange = $this->createCartChange($cartItemCollection);
        $transferCartChange->setDeleteReason($reason);
        $order = $this->zedClient->call('cart/sdk/remove-items', $transferCartChange);
        $this->setOrder($order);

        return $this->zedClient->getLastResponse();
    }

    /**
     * @param CartItem[]|CartItemCollection $cartItemCollection
     * @return Response
     */
    public function changeQuantityOfItems($cartItemCollection)
    {
        $transferCartChange = $this->createCartChange($cartItemCollection);
        $order = $this->zedClient->call('cart/sdk/change-quantity', $transferCartChange);
        $this->setOrder($order);

        return $this->zedClient->getLastResponse();
    }

    /**
     * @param int $reason @see \SprykerFeature_Shared_Library_Cart
     * @return mixed
     */
    public function clear($reason = DeleteReasonConstant::DELETE_REASON_ORDER_PLACEMENT)
    {
        $transferCartChange = $this->createCartChange();
        $transferCartChange->setOrder(new \Generated\Shared\Transfer\OrderTransfer());
        $transferCartChange->setDeleteReason($reason);
        $order = $order = $this->zedClient->call('cart/sdk/clear-cart-storage', $transferCartChange);
        $this->setOrder($order);

        return $this->zedClient->getLastResponse();
    }

    /**
     * @param CartItem[]|CartItemCollection $cartItemCollection
     * @return CartChange
     * @throws \InvalidArgumentException
     */
    public function createCartChange($cartItemCollection = null)
    {
        if ($cartItemCollection === null || is_array($cartItemCollection)) {
            $cartItemCollection = new \Generated\Shared\Transfer\CartItemTransfer();
            $cartItemCollection->fromArray($cartItemCollection);
        } elseif (!$cartItemCollection instanceof CartItemCollection) {
            throw new \InvalidArgumentException('addItems() expects array or CartItemCollection');
        }

        $transferCartChange = new \Generated\Shared\Transfer\ChangeTransfer();
        $transferCartChange->setOrder($this->getOrder());

        if ($this->cartStorage) {
            $transferCartChange->setCartHash($this->cartStorage->getCartHash());
        }

        // TODO set user id on cart change
        $transferCartChange->setChangedCartItems($cartItemCollection);

        return $transferCartChange;
    }

    /**
     * @param  string $couponCode
     * @return Response
     */
    public function addCoupon($couponCode)
    {
        $cartChange = $this->createCartChange();
        $cartChange->setCouponCode($couponCode);
        $order = $this->zedClient->call('cart/sdk/add-coupon-code', $cartChange);
        $this->setOrder($order);

        return $this->zedClient->getLastResponse();
    }

    /**
     * @return Response
     */
    public function clearCoupons()
    {
        $cartChange = $this->createCartChange();
        $order = $this->zedClient->call('cart/sdk/clear-coupon-code', $cartChange);
        $this->setOrder($order);

        return $this->zedClient->getLastResponse();
    }

    /**
     * @param  string $couponCode
     * @return Response
     */
    public function removeCoupon($couponCode)
    {
        $cartChange = $this->createCartChange();
        $cartChange->setCouponCode($couponCode);
        $order = $this->zedClient->call('cart/sdk/remove-coupon-code', $cartChange);
        $this->setOrder($order);

        return $this->zedClient->getLastResponse();
    }

//
////    /**
////     * @return Transfer_Response
////     */
////    public function mergeGuestCartWithCustomerCart()
////    {
////        $transferCartChange = $this->getBasicCartChangeTransfer();
////
////        $response = Generated_Yves_Zed::getInstance()->cartMergeGuestCartWithCustomerCart($transferCartChange);
////        $this->handleCartChangeResponse($response);
////        return $response;
////    }
//
//    /**
//     * Add a coupon to the cart
//     * @param string $coupon
//     * @return Transfer_Response
//     */
//    public function addCoupon($coupon)
//    {
//        $transferCartChange = $this->getBasicCartChangeTransfer();
//        $transferCartChange->setCouponCode($coupon);
//        $response = ZedRequest::getInstance()->cartAddCouponCode($transferCartChange);
//
//        $this->handleCartResponse($response);
//        return $response;
//    }
//
//    /**
//     * Remove coupon from cart
//     * @return Transfer_Response
//     */
//    public function removeCoupon()
//    {
//        $transferCartChange = $this->getBasicCartChangeTransfer();
//
//        $response = ZedRequest::getInstance()->cartRemoveCouponCode($transferCartChange);
//        return $response;
//    }

//    /**
//     * @return bool
//     */
//    public function recalculate()
//    {
//        $change = \SprykerFeature_Shared_Library_Factory::loadCartChange();
//        $change->setOrder($this->loadSalesOrder());
//
//        $response = Generated_Yves_Zed::getInstance()->cartRecalculate($change);
//
//        if ($response->getSuccess()) {
//            $this->setSalesOrder(\SprykerFeature_Shared_Library_Factory::loadSalesOrder($response->getTransfer(), true));
//            return true;
//        }
//
//        return false;
//    }
}
