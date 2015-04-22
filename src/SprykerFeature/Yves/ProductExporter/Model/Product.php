<?php

namespace SprykerFeature\Yves\ProductExporter\Model;

use SprykerFeature\Shared\Product\Model\ProductInterface;

/**
 * Class Product
 * @package SprykerFeature\Yves\ProductExport\Model
 */
class Product implements ProductInterface
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var bool
     */
    protected $isActive = true;

    /**
     * @var string
     */
    protected $sku = '';

    /**
     * @var string
     */
    protected $name = '';

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
     * @param string    $name
     * @param mixed     $value
     */
    public function addAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive();
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

    /**
     * @param string $name
     *
     * @return null|mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return null;
    }

    /**
     * @param string    $name
     * @param mixed     $arguments
     *
     * @return null|mixed
     */
    public function __call($name, $arguments)
    {
        return $this->__get($name);
    }
}
