<?php

namespace SprykerFeature\Shared\Price\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Product extends AbstractTransfer
{

    protected $idPriceProduct = null;

    protected $price = null;

    protected $isActive = null;

    protected $priceTypeName = null;

    protected $skuProduct = null;


    /**
     * @return null
     */
    public function getSkuProduct()
    {
        return $this->skuProduct;
    }

    /**
     * @param string $skuProduct
     * @return $this
     */
    public function setSkuProduct($skuProduct)
    {
        $this->skuProduct = $skuProduct;
        $this->addModifiedProperty('skuProduct');
        return $this;
    }

    /**
     * @return null
     */
    public function getIdPriceProduct()
    {
        return $this->idPriceProduct;
    }

    /**
     * @param $idPriceProduct
     * @return $this
     */
    public function setIdPriceProduct($idPriceProduct)
    {
        $this->idPriceProduct = $idPriceProduct;
        $this->addModifiedProperty('idPriceProduct');
        return $this;
    }

    /**
     * @return null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        $this->addModifiedProperty('price');
        return $this;
    }

    /**
     * @return null
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        $this->addModifiedProperty('isActive');
        return $this;
    }

    /**
     * @return null
     */
    public function getPriceTypeName()
    {
        return $this->priceTypeName;
    }

    /**
     * @param $priceTypeName
     * @return $this
     */
    public function setPriceTypeName($priceTypeName)
    {
        $this->priceTypeName = $priceTypeName;
        $this->addModifiedProperty('priceTypeName');
        return $this;
    }

}
