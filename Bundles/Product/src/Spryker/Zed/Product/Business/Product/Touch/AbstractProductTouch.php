<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Touch;

use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface;
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
     * @var \Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface
     */
    protected $productAbstractStatusChecker;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface $productAbstractStatusChecker
     */
    public function __construct(ProductToTouchInterface $touchFacade, ProductQueryContainerInterface $productQueryContainer, ProductAbstractStatusCheckerInterface $productAbstractStatusChecker)
    {
        $this->touchFacade = $touchFacade;
        $this->productQueryContainer = $productQueryContainer;
        $this->productAbstractStatusChecker = $productAbstractStatusChecker;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function touchAbstractByStatus($idProductAbstract)
    {
        if ($this->productAbstractStatusChecker->isActive($idProductAbstract)) {
            $this->touchProductAbstractActive($idProductAbstract);
        } else {
            $this->touchProductAbstractDeleted($idProductAbstract);
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
            $this->touchProductConcreteActive($concreteProductEntity->getIdProduct());
        } else {
            $this->touchProductConcreteDeleted($concreteProductEntity->getIdProduct());
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductAbstractActive($idProductAbstract)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $this->touchFacade->touchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->touchFacade->touchActive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductAbstractInactive($idProductAbstract)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $this->touchFacade->touchInactive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->touchFacade->touchInactive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductAbstractDeleted($idProductAbstract)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $this->touchFacade->touchDeleted(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->touchFacade->touchDeleted(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteActive($idProductConcrete)
    {
        $this->touchFacade->touchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE, $idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteInactive($idProductConcrete)
    {
        $this->touchFacade->touchInactive(ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE, $idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteDeleted($idProductConcrete)
    {
        $this->touchFacade->touchDeleted(ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE, $idProductConcrete);
    }
}
