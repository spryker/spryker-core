<?php
namespace SprykerFeature\Yves\Cart\Model;

use SprykerFeature\Shared\Cart\Code\DeleteReasonConstant;
use Generated\Shared\Transfer\CartCartItemTransfer;
use SprykerFeature\Shared\Library\Communication\Response;
use Generated\Shared\Transfer\SalesOrderTransfer;

interface CartInterface
{

    /**
     * @return \SprykerFeature\Shared\Sales\Transfer\OrderItem[]|\SprykerFeature\Shared\Sales\Transfer\OrderItemCollection
     */
    public function getItems();

    /**
     * @return int
     */
    public function getCount();

    /**
     * @param CartItem $cartItem
     * @return Response
     */
    public function addItem(CartItem $cartItem);

    /**
     * @param CartItem[]|CartItemCollection $cartItemCollection
     * @return Response
     */
    public function addItems($cartItemCollection);

    /**
     * @param CartItem $cartItem
     * @param int $reason
     * @return Response
     */
    public function removeItem(CartItem $cartItem, $reason = DeleteReasonConstant::DELETE_REASON_ACTIVELY_REMOVED_BY_USER);

    /**
     * @param CartItem[]|CartItemCollection $cartItemCollection
     * @param int $reason
     * @return Response
     */
    public function removeItems($cartItemCollection, $reason = DeleteReasonConstant::DELETE_REASON_ACTIVELY_REMOVED_BY_USER);

    /**
     * @param  CartItem $cartItem
     * @return Response
     */
    public function changeQuantityOfItem(CartItem $cartItem);

    /**
     * @param CartItem[]|CartItemCollection $cartItemCollection
     * @return Response
     */
    public function changeQuantityOfItems($cartItemCollection);

    /**
     * @param int $reason
     * @return
     */
    public function clear($reason = DeleteReasonConstant::DELETE_REASON_ORDER_PLACEMENT);

    /**
     * @param string $couponCode
     * @return Response
     */
    public function addCoupon($couponCode);

    /**
     * @param string $couponCode
     * @return Response
     */
    public function removeCoupon($couponCode);

    /**
     * @return Response
     */
    public function clearCoupons();

    /**
     * @return Order
     */
    public function getOrder();

    /**
     * @param Order $order
     */
    public function setOrder(Order $order);
}
