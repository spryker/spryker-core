<?php

namespace SprykerFeature\Shared\Product\Model;

/**
 * Class Product
 *
 * @package SprykerFeature\Zed\Product\Business\Model
 */
interface ProductInterface
{
    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes);

    /**
     * @return boolean
     */
    public function isActive();

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive = true);

    /**
     * @return string
     */
    public function getSku();

    /**
     * @param string $sku
     */
    public function setSku($sku);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);
}