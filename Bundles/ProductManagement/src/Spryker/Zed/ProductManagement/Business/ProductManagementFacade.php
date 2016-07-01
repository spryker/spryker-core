<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementBusinessFactory getFactory()
 */
class ProductManagementFacade extends AbstractFacade implements ProductManagementFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer[] $productConcreteCollection
     *
     * @throws \Exception
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getFactory()
            ->createProductManager()
            ->addProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer[] $productConcreteCollection
     *
     * @throws \Exception
     *
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getFactory()
            ->createProductManager()
            ->saveProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer[]
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductManager()
            ->getConcreteProductsByAbstractProductId($idProductAbstract);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getProductAttributeCollection()
    {
        return $this->getFactory()
            ->createProductManager()
            ->getProductAttributeCollection();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getProductAttributeGroupCollection()
    {
        return $this->getFactory()
            ->createProductManager()
            ->getProductAttributeGroupCollection();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function getProductAbstractById($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductManager()
            ->getProductAbstractById($idProductAbstract);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAttributesByAbstractProductId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductManager()
            ->getProductAttributesByAbstractProductId($idProductAbstract);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getProductAttributesTypeCollection()
    {
        return $this->getFactory()
            ->createAttributeManager()
            ->getProductAttributesTypeCollection();
    }

}
