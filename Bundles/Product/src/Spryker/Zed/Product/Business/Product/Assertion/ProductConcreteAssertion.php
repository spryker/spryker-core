<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Assertion;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductConcreteAssertion implements ProductConcreteAssertionInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return void
     */
    public function assertSkuIsUnique($sku)
    {
        $productExists = $this->productQueryContainer
            ->queryProductConcreteBySku($sku)
            ->count() > 0;

        if ($productExists) {
            throw new ProductConcreteExistsException(sprintf(
                'Product concrete with sku %s already exists',
                $sku
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

    /**
     * @param int $idProduct
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return void
     */
    public function assertProductExists($idProduct)
    {
        $productExists = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProduct)
            ->count() > 0;

        if (!$productExists) {
            throw new MissingProductException(sprintf(
                'Product concrete with id "%s" does not exist.',
                $idProduct
            ));
        }
    }
}
