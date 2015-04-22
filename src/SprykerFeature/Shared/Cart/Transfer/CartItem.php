<?php 

namespace SprykerFeature\Shared\Cart\Transfer;

/**
 *
 */
class CartItem extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $sku = null;

    protected $uniqueIdentifier = null;

    protected $options = array(
        
    );

    protected $quantity = null;

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        $this->addModifiedProperty('sku');
        return $this;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $uniqueIdentifier
     * @return $this
     */
    public function setUniqueIdentifier($uniqueIdentifier)
    {
        $this->uniqueIdentifier = $uniqueIdentifier;
        $this->addModifiedProperty('uniqueIdentifier');
        return $this;
    }

    /**
     * @return string
     */
    public function getUniqueIdentifier()
    {
        return $this->uniqueIdentifier;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        $this->addModifiedProperty('options');
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $option
     * @return array
     */
    public function addOption($option)
    {
        $this->options[] = $option;
        return $this;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->addModifiedProperty('quantity');
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }


}
