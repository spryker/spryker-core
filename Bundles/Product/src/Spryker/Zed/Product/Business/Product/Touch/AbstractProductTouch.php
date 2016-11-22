<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Touch;

use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Zed\Product\Business\Product\ProductManagerInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

abstract class AbstractProductTouch
{

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductManagerInterface
     */
    protected $productManager;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Business\Product\ProductManagerInterface $productManager
     */
    public function __construct(ProductToTouchInterface $touchFacade, ProductQueryContainerInterface $productQueryContainer, ProductManagerInterface $productManager)
    {
        $this->touchFacade = $touchFacade;
        $this->productQueryContainer = $productQueryContainer;
        $this->productManager = $productManager;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function touchAbstractByStatus($idProductAbstract)
    {
        if ($this->productManager->isProductActive($idProductAbstract)) {
            $this->touchFacade->touchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
            $this->touchFacade->touchActive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);
        } else {
            $this->touchFacade->touchInactive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
            $this->touchFacade->touchInactive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $concreteProductEntity
     *
     * @return void
     */
    protected function touchConcreteByStatus(SpyProduct $concreteProductEntity)
    {
        if ($concreteProductEntity->getIsActive()) {
            $this->touchFacade->touchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE, $concreteProductEntity->getIdProduct());
        } else {
            $this->touchFacade->touchInactive(ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE, $concreteProductEntity->getIdProduct());
        }
    }

}
