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
    public function getAttributes();

    /**
     * @param array $attributes
     *
     * @return void
     */
    public function setAttributes(array $attributes);

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
    public function getSku();

    /**
     * @param string $sku
     *
     * @return void
     */
    public function setSku($sku);

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
     * @return array
     */
    public function getId();

    /**
     * @param array $id
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getPrice();

    /**
     * @param int $price
     */
    public function setPrice($price);

}
