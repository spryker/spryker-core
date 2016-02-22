<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Product\Model;

/**
 * @deprecated 1.0.0 Will be removed in the next major version. Please use Transfer objects instead.
 */
interface ProductAbstractInterface
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
    public function getProductConcreteCollection();

    /**
     * @param array $products
     */
    public function setProductConcreteCollection(array $products);

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
