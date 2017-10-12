<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Assertion;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractAssertion implements ProductAbstractAssertionInterface
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
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return void
     */
    public function assertSkuIsUnique($sku)
    {
        $productExists = $this->productQueryContainer
            ->queryProductAbstractBySku($sku)
            ->count() > 0;

        if ($productExists) {
            throw new ProductAbstractExistsException(sprintf(
                'Product abstract with sku %s already exists',
                $sku
            ));
        }
    }

    /**
     * @param int $idProductAbstract
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
        $productExists = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->count() > 0;

        if (!$productExists) {
            throw new MissingProductException(sprintf(
                'Product abstract with id "%s" does not exist.',
                $idProductAbstract
            ));
        }
    }
}
