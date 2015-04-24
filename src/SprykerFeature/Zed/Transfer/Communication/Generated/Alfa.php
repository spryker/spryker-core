<?php

namespace Generated;

use Cart\CartChangeTransferInterface;
use Demo\DemoTransferInterface;
use AlfaItem;
use CartItem[];
use Sales\Order;
use Demo\Customer;

/**
 * Class Alfa
 *
 * @author SprykerGenerator
 */
class Alfa implements CartChangeTransferInterface, DemoTransferInterface, DemoTransferInterface
{
    
    /**
     * @var AlfaItem  $AlfaItem
     */
    protected $AlfaItem;
    
    /**
     * @var CartItem[]  $changedCartItems
     */
    protected $changedCartItems;
    
    /**
     * @var $couponCode
     */
    protected $couponCode = 'AAA';
    
    /**
     * @var Order  $order
     */
    protected $order;
    
    /**
     * @var $userId
     */
    protected $userId;
    
    /**
     * @var $cartHash
     */
    protected $cartHash;
    
    /**
     * @var $isMerged
     */
    protected $isMerged;
    
    /**
     * @var Customer  $customer
     */
    protected $customer;
    
    /**
     * @var Customer  $hgghtg
     */
    protected $hgghtg;
    
    
    /**
     * @var $AlfaItem
     */
    public function setAlfaItem(AlfaItem $AlfaItem)
    {
        $this->AlfaItem = $AlfaItem;
    }

    /**
    * @return $AlfaItem
    */
    public function getAlfaItem()
    {
        return $this->AlfaItem;
    }
    
    /**
     * @var $changedCartItems
     */
    public function setChangedCartItems(CartItem[] $changedCartItems)
    {
        $this->changedCartItems = $changedCartItems;
    }

    /**
    * @return $changedCartItems
    */
    public function getChangedCartItems()
    {
        return $this->changedCartItems;
    }
    
    /**
     * @var $couponCode
     */
    public function setCouponCode($couponCode)
    {
        $this->couponCode = $couponCode;
    }

    /**
    * @return $couponCode
    */
    public function getCouponCode()
    {
        return $this->couponCode;
    }
    
    /**
     * @var $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
    * @return $order
    */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * @var $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
    * @return $userId
    */
    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     * @var $cartHash
     */
    public function setCartHash($cartHash)
    {
        $this->cartHash = $cartHash;
    }

    /**
    * @return $cartHash
    */
    public function getCartHash()
    {
        return $this->cartHash;
    }
    
    /**
     * @var $isMerged
     */
    public function setIsMerged($isMerged)
    {
        $this->isMerged = $isMerged;
    }

    /**
    * @return $isMerged
    */
    public function getIsMerged()
    {
        return $this->isMerged;
    }
    
    /**
     * @var $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
    * @return $customer
    */
    public function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     * @var $hgghtg
     */
    public function setHgghtg(Customer $hgghtg)
    {
        $this->hgghtg = $hgghtg;
    }

    /**
    * @return $hgghtg
    */
    public function getHgghtg()
    {
        return $this->hgghtg;
    }
    
}
