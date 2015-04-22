<?php

namespace SprykerFeature\Shared\Stock\Transfer;

class StockProduct extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{
    protected $sku = null;

    protected $stockType = null;

    protected $quantity = null;

    protected $isNeverOutOfStock = null;

    protected $idStockProduct = null;

    /**
     * @return null
     */
    public function getIdStockProduct()
    {
        return $this->idStockProduct;
    }

    /**
     * @param int $idStockProduct
     * @return $this
     */
    public function setIdStockProduct($idStockProduct)
    {
        $this->idStockProduct = $idStockProduct;
        $this->addModifiedProperty('idStockProduct');
        return $this;
    }

    /**
     * @return null
     */
    public function getSku()
    {
        return $this->sku;
    }

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
     * @return null
     */
    public function getQuantity()
    {
        return $this->quantity;
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
     * @return null
     */
    public function getIsNeverOutOfStock()
    {
        return $this->isNeverOutOfStock;
    }

    /**
     * @param bool $isNeverOutOfStock
     * @return $this
     */
    public function setIsNeverOutOfStock($isNeverOutOfStock)
    {
        $this->isNeverOutOfStock = $isNeverOutOfStock;
        $this->addModifiedProperty('isNeverOutOfStock');
        return $this;
    }

    /**
     * @return null
     */
    public function getStockType()
    {
        return $this->stockType;
    }

    /**
     * @param int $stockType
     * @return $this
     */
    public function setStockType($stockType)
    {
        $this->stockType = $stockType;
        $this->addModifiedProperty('stockType');
        return $this;
    }

}
