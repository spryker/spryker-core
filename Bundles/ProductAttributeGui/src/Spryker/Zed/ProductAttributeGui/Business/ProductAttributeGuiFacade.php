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
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributes($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getProductAbstractAttributes($idProductAbstract);
    }

    /**
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

}
