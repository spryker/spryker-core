<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Product\Model;

interface AbstractProductInterface
{

    /**
     * @return array
     */
    public function getAbstractAttributes();

    /**
     * @param array $attributes
     */
    public function setAbstractAttributes(array $attributes);

    /**
     * @return array
     */
    public function getConcreteProducts();

    /**
     * @param array $products
     */
    public function setConcreteProducts(array $products);

    /**
     * @return bool
     */
    public function isActive();

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive = true);

    /**
     * @return string
     */
    public function getAbstractSku();

    /**
     * @param string $sku
     */
    public function setAbstractSku($sku);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

}
