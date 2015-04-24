<?php

namespace Generated;

use Cart\CartChangeTransferInterface;
use Demo\DemoTransferInterface;
use AlfaItem;
use CartItem[];
use Sales\Order;
use Demo\Customer;
use Cart\CartItemTransferInterface;
use array;

/**
 * Class AlfaItem
 *
 * @author SprykerGenerator
 */
class AlfaItem implements CartItemTransferInterface
{
    
    /**
     * @var $sku
     */
    protected $sku;
    
    /**
     * @var $uniqueIdentifier
     */
    protected $uniqueIdentifier;
    
    /**
     * @var array  $options
     */
    protected $options;
    
    /**
     * @var $quantity
     */
    protected $quantity;
    
    
    /**
     * @var $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
    * @return $sku
    */
    public function getSku()
    {
        return $this->sku;
    }
    
    /**
     * @var $uniqueIdentifier
     */
    public function setUniqueIdentifier($uniqueIdentifier)
    {
        $this->uniqueIdentifier = $uniqueIdentifier;
    }

    /**
    * @return $uniqueIdentifier
    */
    public function getUniqueIdentifier()
    {
        return $this->uniqueIdentifier;
    }
    
    /**
     * @var $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
    * @return $options
    */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * @var $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
    * @return $quantity
    */
    public function getQuantity()
    {
        return $this->quantity;
    }
    
}
