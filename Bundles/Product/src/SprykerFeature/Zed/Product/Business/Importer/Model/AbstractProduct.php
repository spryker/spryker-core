<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Model;

use SprykerFeature\Shared\Product\Model\ProductInterface;

/**
 * Class Product
 *
 * @package SprykerFeature\Zed\Product\Business\Model
 */
class AbstractProduct implements ProductInterface
{
    /**
     * @var string
     */
    protected $sku;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $isActive = false;

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive = true)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
 