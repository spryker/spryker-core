<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Product;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductConcreteAssertion implements ProductConcreteAssertionInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(
        ProductManagementToProductInterface $productFacade,
        ProductQueryContainerInterface $productQueryContainer
    ) {
        $this->productFacade = $productFacade;
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return void
     */
    public function assertSkuUnique($sku)
    {
        if ($this->productFacade->hasProductConcrete($sku)) {
            throw new ProductConcreteExistsException(sprintf(
                'Product concrete with sku %s already exists',
                $sku
            ));
        }
    }

    /**
     * @param int $idProduct
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return void
     */
    public function assertProductExists($idProduct)
    {
        $productEntity = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProduct)
            ->findOne();

        if (!$productEntity) {
            throw new MissingProductException(sprintf(
                'Product concrete with id "%s" does not exist.',
                $idProduct
            ));
        }
    }

    /**
     * @param int $idProduct
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return void
     */
    public function assertSkuIsUniqueWhenUpdatingProduct($idProduct, $sku)
    {
        $isUnique = $this->productQueryContainer
            ->queryProductConcreteBySku($sku)
            ->filterByIdProduct($idProduct, Criteria::NOT_EQUAL)
            ->count() <= 0;

        if (!$isUnique) {
            throw new ProductConcreteExistsException(sprintf(
                'Product concrete with sku %s already exists',
                $sku
            ));
        }
    }

}
