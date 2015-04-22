<?php 

namespace SprykerFeature\Shared\Cart\Transfer;

/**
 *
 */
class CartChange extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $changedCartItems = 'Cart\\CartItemCollection';

    protected $couponCode = null;

    protected $order = 'Sales\\Order';

    protected $userId = null;

    protected $cartHash = null;

    protected $deleteReason = null;

    protected $isMerged = null;

    /**
     * @param \SprykerFeature\Shared\Cart\Transfer\CartItemCollection $changedCartItems
     * @return $this
     */
    public function setChangedCartItems(\SprykerFeature\Shared\Cart\Transfer\CartItemCollection $changedCartItems)
    {
        $this->changedCartItems = $changedCartItems;
        $this->addModifiedProperty('changedCartItems');
        return $this;
    }

    /**
     * @return \SprykerFeature\Shared\Cart\Transfer\CartItem[]|\SprykerFeature\Shared\Cart\Transfer\CartItemCollection
     */
    public function getChangedCartItems()
    {
        return $this->changedCartItems;
    }

    /**
     * @param \SprykerFeature\Shared\Cart\Transfer\CartItem $changedCartItem
     * @return \SprykerFeature\Shared\Cart\Transfer\CartItemCollection
     */
    public function addChangedCartItem(\SprykerFeature\Shared\Cart\Transfer\CartItem $changedCartItem)
    {
        $this->changedCartItems->add($changedCartItem);
        return $this;
    }

    /**
     * @param \SprykerFeature\Shared\Cart\Transfer\CartItem $changedCartItem
     * @return \SprykerFeature\Shared\Cart\Transfer\CartItemCollection
     */
    public function removeChangedCartItem(\SprykerFeature\Shared\Cart\Transfer\CartItem $changedCartItem)
    {
        $this->changedCartItems->remove($changedCartItem);
        return $this;
    }

    /**
     * @param string $couponCode
     * @return $this
     */
    public function setCouponCode($couponCode)
    {
        $this->couponCode = $couponCode;
        $this->addModifiedProperty('couponCode');
        return $this;
    }

    /**
     * @return string
     */
    public function getCouponCode()
    {
        return $this->couponCode;
    }

    /**
     * @param \SprykerFeature\Shared\Sales\Transfer\Order $order
     * @return $this
     */
    public function setOrder(\SprykerFeature\Shared\Sales\Transfer\Order $order)
    {
        $this->order = $order;
        $this->addModifiedProperty('order');
        return $this;
    }

    /**
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        $this->addModifiedProperty('userId');
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $cartHash
     * @return $this
     */
    public function setCartHash($cartHash)
    {
        $this->cartHash = $cartHash;
        $this->addModifiedProperty('cartHash');
        return $this;
    }

    /**
     * @return string
     */
    public function getCartHash()
    {
        return $this->cartHash;
    }

    /**
     * @param string $deleteReason
     * @return $this
     */
    public function setDeleteReason($deleteReason)
    {
        $this->deleteReason = $deleteReason;
        $this->addModifiedProperty('deleteReason');
        return $this;
    }

    /**
     * @return string
     */
    public function getDeleteReason()
    {
        return $this->deleteReason;
    }

    /**
     * @param bool $isMerged
     * @return $this
     */
    public function setIsMerged($isMerged)
    {
        $this->isMerged = $isMerged;
        $this->addModifiedProperty('isMerged');
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsMerged()
    {
        return $this->isMerged;
    }


}
