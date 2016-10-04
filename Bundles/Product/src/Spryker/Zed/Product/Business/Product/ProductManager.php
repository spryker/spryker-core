<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductManager implements ProductManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface
     */
    protected $attributeManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    protected $touchManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductUrlManager
     */
    protected $urlManager;

    public function __construct(
        AttributeManagerInterface $attributeManager,
        ProductAbstractManagerInterface $productAbstractManager,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductQueryContainerInterface $productQueryContainer,
        ProductToTouchInterface $touchFacade,
        ProductToUrlInterface $urlFacade,
        ProductToLocaleInterface $localeFacade
    ) {
        $this->attributeManager = $attributeManager;
        $this->productAbstractManager = $productAbstractManager;
        $this->productConcreteManager = $productConcreteManager;
        $this->productQueryContainer = $productQueryContainer; //TODO replace it with just connection instance, or move the logic into separate class
        $this->touchFacade = $touchFacade;
        $this->urlFacade = $urlFacade;
        $this->localeFacade = $localeFacade;
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

}
