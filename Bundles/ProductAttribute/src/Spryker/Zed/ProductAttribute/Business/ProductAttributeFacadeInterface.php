<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business;

/**
 * @method \Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 */
interface ProductAttributeFacadeInterface
{

    /**
     * Specification:
     * - Returns product abstract attributes with metadata info
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributeValues($idProductAbstract);

    /**
     * Specification:
     * - Returns list of all product concrete attributes
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return array
     */
    public function getProductAttributeValues($idProduct);

    /**
     * Specification:
     * - Returns list of attributes metadata based on product abstract attributes
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributesForProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Returns list of attributes metadata based on product abstract attributes
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return array
     */
    public function getMetaAttributesForProduct($idProduct);

    /**
     * Specification:
     * - Returns basic product abstract transfer
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function getProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Returns basic product concrete transfer
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProduct($idProduct);

    /**
     * Specification:
     * - Returns list of product keys suggested based on $searchText, super attributes are ignored
     *
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText = '', $limit = 10);

    /**
     * Specification:
     * - Save product abstract attributes
     *
     * $attributes format
     * [
     *   [default] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param array $attributes
     *
     * @return void
     */
    public function saveAbstractAttributes($idProductAbstract, array $attributes);

    /**
     * Specification:
     * - Save product concrete attributes
     *
     * $attributes format
     * [
     *   [default] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @api
     *
     * @param int $idProduct
     * @param array $attributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes);

    /**
     * Specification:
     * - Return list of attribute keys
     *
     * $attributes format
     * [
     *   [default] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @api
     *
     * @param array $attributes
     *
     * @return array
     */
    public function extractKeysFromAttributes(array $attributes);

    /**
     * Specification:
     * - Return list of attribute values
     *
     * $attributes format
     * [
     *   [default] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @api
     *
     * @param array $attributes
     *
     * @return array
     */
    public function extractValuesFromAttributes(array $attributes);

}
