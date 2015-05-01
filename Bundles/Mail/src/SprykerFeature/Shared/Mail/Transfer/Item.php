<?php 

namespace SprykerFeature\Shared\Mail\Transfer;

/**
 *
 */
class Item extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idSalesOrderItem = null;

    protected $name = null;

    protected $sku = null;

    protected $grossPrice = null;

    protected $totalPrice = null;

    protected $quantity = null;

    /**
     * @param int $idSalesOrderItem
     * @return $this
     */
    public function setIdSalesOrderItem($idSalesOrderItem)
    {
        $this->idSalesOrderItem = $idSalesOrderItem;
        $this->addModifiedProperty('idSalesOrderItem');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdSalesOrderItem()
    {
        return $this->idSalesOrderItem;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $sku
     * @return $this
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        $this->addModifiedProperty('sku');
        return $this;
    }

    /**
     * @return int
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param int $grossPrice
     * @return $this
     */
    public function setGrossPrice($grossPrice)
    {
        $this->grossPrice = $grossPrice;
        $this->addModifiedProperty('grossPrice');
        return $this;
    }

    /**
     * @return int
     */
    public function getGrossPrice()
    {
        return $this->grossPrice;
    }

    /**
     * @param int $totalPrice
     * @return $this
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
        $this->addModifiedProperty('totalPrice');
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
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
