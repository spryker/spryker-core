<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductManager implements ProductManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    public function __construct(
        ProductAbstractManagerInterface $productAbstractManager,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductQueryContainerInterface $productQueryContainer
    ) {
        $this->productAbstractManager = $productAbstractManager;
        $this->productConcreteManager = $productConcreteManager;
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $idProductAbstract = $this->productAbstractManager->createProductAbstract($productAbstractTransfer);
        $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        foreach ($productConcreteCollection as $productConcrete) {
            $productConcrete->setFkProductAbstract($idProductAbstract);
            $this->productConcreteManager->createProductConcrete($productConcrete);
        }

        $this->productQueryContainer->getConnection()->commit();

        return $idProductAbstract;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array|\Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $idProductAbstract = $this->productAbstractManager->saveProductAbstract($productAbstractTransfer);

        foreach ($productConcreteCollection as $productConcreteTransfer) {
            $productConcreteTransfer->setFkProductAbstract($idProductAbstract);

            $productConcreteEntity = $this->productConcreteManager->findProductEntityByAbstract(
                $productAbstractTransfer,
                $productConcreteTransfer
            );

            if ($productConcreteEntity) {
                $productConcreteTransfer->setIdProductConcrete($productConcreteEntity->getIdProduct());
                $this->productConcreteManager->saveProductConcrete($productConcreteTransfer);
            } else {
                $this->productConcreteManager->createProductConcrete($productConcreteTransfer);
            }
        }

        $this->productQueryContainer->getConnection()->commit();

        return $idProductAbstract;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductActive($idProductAbstract)
    {
        $productConcreteCollection = $this->productConcreteManager->getConcreteProductsByAbstractProductId(
            $idProductAbstract
        );

        foreach ($productConcreteCollection as $productConcreteTransfer) {
            if ($productConcreteTransfer->getIsActive()) {
                return true;
            }
        }

        return false;
    }

}
