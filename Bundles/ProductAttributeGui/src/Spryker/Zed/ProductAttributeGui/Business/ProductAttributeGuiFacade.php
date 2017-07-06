<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Business\ProductAttributeGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class ProductAttributeGuiFacade extends AbstractFacade implements ProductAttributeGuiFacadeInterface
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
    public function getProductAbstractAttributeValues($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getProductAbstractAttributeValues($idProductAbstract);
    }

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
    public function getProductAttributeValues($idProduct)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getProductAttributeValues($idProduct);
    }

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
    public function getMetaAttributesForProductAbstract($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getMetaAttributesForProductAbstract($idProductAbstract);
    }

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
    public function getMetaAttributesForProduct($idProduct)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getMetaAttributesForProduct($idProduct);
    }

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
    public function getProductAbstract($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getProductAbstract($idProductAbstract);
    }

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
    public function getProduct($idProduct)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getProduct($idProduct);
    }

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
    public function suggestKeys($searchText = '', $limit = 10)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->suggestKeys($searchText, $limit);
    }

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
    public function saveAbstractAttributes($idProductAbstract, array $attributes)
    {
        $this->getFactory()
            ->createAttributeWriter()
            ->saveAbstractAttributes($idProductAbstract, $attributes);
    }

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
    public function saveConcreteAttributes($idProduct, array $attributes)
    {
        $this->getFactory()
            ->createAttributeWriter()
            ->saveConcreteAttributes($idProduct, $attributes);
    }

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
    public function extractKeysFromAttributes(array $productAttributes)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->extractKeysFromAttributes($productAttributes);
    }

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
    public function extractValuesFromAttributes(array $productAttributes)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->extractValuesFromAttributes($productAttributes);
    }

}
