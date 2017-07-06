<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Business\ProductAttributeGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
interface ProductAttributeGuiFacadeInterface
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
     * @api
     *
     * @param array $productAttributes
     *
     * @return array
     */
    public function extractKeysFromAttributes(array $productAttributes);

    /**
     * Specification:
     * - Return list of attribute values
     *
     * @api
     *
     * @param array $productAttributes
     *
     * @return array
     */
    public function extractValuesFromAttributes(array $productAttributes);

}
