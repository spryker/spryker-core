<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Product;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractAssertion implements ProductAbstractAssertionInterface
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
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return void
     */
    public function assertSkuIsUnique($sku)
    {
        if ($this->productFacade->hasProductAbstract($sku)) {
            throw new ProductAbstractExistsException(sprintf(
                'Product abstract with sku %s already exists',
                $sku
            ));
        }
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return void
     */
    public function assertSkuIsUniqueWhenUpdatingProduct($idProductAbstract, $sku)
    {
        $isUnique = $this->productQueryContainer
            ->queryProductAbstractBySku($sku)
            ->filterByIdProductAbstract($idProductAbstract, Criteria::NOT_EQUAL)
            ->count() <= 0;

        if (!$isUnique) {
            throw new ProductAbstractExistsException(sprintf(
                'Product abstract with sku %s already exists',
                $sku
            ));
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return void
     */
    public function assertProductExists($idProductAbstract)
    {
        $productAbstractEntity = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();

        if (!$productAbstractEntity) {
            throw new MissingProductException(sprintf(
                'Product abstract with id "%s" does not exist.',
                $idProductAbstract
            ));
        }
    }

}
