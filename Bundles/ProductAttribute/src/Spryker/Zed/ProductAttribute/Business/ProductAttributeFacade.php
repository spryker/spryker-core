<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 */
class ProductAttributeFacade extends AbstractFacade implements ProductAttributeFacadeInterface
{

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $attributes
     *
     * @return array
     */
    public function extractKeysFromAttributes(array $attributes)
    {
        return $this->getFactory()
            ->createAttributeMapper()
            ->extractKeysFromAttributes($attributes);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $attributes
     *
     * @return array
     */
    public function extractValuesFromAttributes(array $attributes)
    {
        return $this->getFactory()
            ->createAttributeMapper()
            ->extractValuesFromAttributes($attributes);
    }

}
