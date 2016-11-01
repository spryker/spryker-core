<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Product\Model;

/**
 * //TODO: Will be removed in the next major version. Please use Transfer objects instead.
 */
interface ProductAbstractInterface
{

    /**
     * @return array
     */
    public function getAbstractAttributes();

    /**
     * @param array $attributes
     *
     * @return void
     */
    public function setAbstractAttributes(array $attributes);

    /**
     * @return array
     */
    public function getProductConcreteCollection();

    /**
     * @param array $products
     *
     * @return void
     */
    public function setProductConcreteCollection(array $products);

    /**
     * @return bool
     */
    public function isActive();

    /**
     * @param bool $isActive
     *
     * @return void
     */
    public function setIsActive($isActive = true);

    /**
     * @return string
     */
    public function getAbstractSku();

    /**
     * @param string $sku
     *
     * @return void
     */
    public function setAbstractSku($sku);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName($name);

    /**
     * @param string $abstractProductId
     */
    public function setAbstractProductId($abstractProductId);

    /**
     * @return string
     */
    public function getAbstractProductId();

}
