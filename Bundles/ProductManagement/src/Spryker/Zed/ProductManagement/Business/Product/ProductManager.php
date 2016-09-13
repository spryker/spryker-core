<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ZedProductConcreteTransfer;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class ProductManager implements ProductManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    public function __construct(
        ProductAbstractManagerInterface $productAbstractManagerInterface,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductManagementQueryContainerInterface $productManagementQueryContainer
    ) {

        $this->productAbstractManager = $productAbstractManagerInterface;
        $this->productConcreteManager = $productConcreteManager;
        $this->productManagementQueryContainer = $productManagementQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->productAbstractManager->createProductAbstract($productAbstractTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->productAbstractManager->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function getProductAbstractIdBySku($sku)
    {
        return $this->productAbstractManager->getProductAbstractIdBySku($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    public function createProductConcrete(ZedProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->productConcreteManager->createProductConcrete($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    public function saveProductConcrete(ZedProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->productConcreteManager->saveProductConcrete($productConcreteTransfer);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function getProductAbstractById($idProductAbstract)
    {
        return $this->productAbstractManager->getProductAbstractById($idProductAbstract);
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer|null
     */
    public function getProductConcreteById($idProduct)
    {
        return $this->productConcreteManager->getProductConcreteById($idProduct);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer[] $productConcreteCollection
     *
     * @throws \Exception
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        $this->productManagementQueryContainer->getConnection()->beginTransaction();

        try {
            $idProductAbstract = $this->createProductAbstract($productAbstractTransfer);
            $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

            foreach ($productConcreteCollection as $productConcrete) {
                $productConcrete->setFkProductAbstract($idProductAbstract);
                $this->createProductConcrete($productConcrete);
            }

            $this->productManagementQueryContainer->getConnection()->commit();

            return $idProductAbstract;

        } catch (\Exception $e) {
            $this->productManagementQueryContainer->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array|\Generated\Shared\Transfer\ZedProductConcreteTransfer[] $productConcreteCollection
     *
     * @throws \Exception
     *
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        $this->productManagementQueryContainer->getConnection()->beginTransaction();

        try {
            $idProductAbstract = $this->saveProductAbstract($productAbstractTransfer);

            foreach ($productConcreteCollection as $productConcreteTransfer) {
                $productConcreteTransfer->setFkProductAbstract($idProductAbstract);

                $productConcreteEntity = $this->productConcreteManager->findProductEntityByAbstract($productAbstractTransfer, $productConcreteTransfer);
                if ($productConcreteEntity) {
                    $productConcreteTransfer->setIdProductConcrete($productConcreteEntity->getIdProduct());
                    $this->saveProductConcrete($productConcreteTransfer);
                } else {
                    $this->createProductConcrete($productConcreteTransfer);
                }
            }

            $this->productManagementQueryContainer->getConnection()->commit();

            return $idProductAbstract;

        } catch (\Exception $e) {
            $this->productManagementQueryContainer->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer[]
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract)
    {
        return $this->productConcreteManager->getConcreteProductsByAbstractProductId($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributesByAbstractProductId($idProductAbstract)
    {
        return $this->productAbstractManager->getProductAttributesByAbstractProductId($idProductAbstract);
    }

}
