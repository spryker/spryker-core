<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\Product;

use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface;

class ProductFinder implements ProductFinderInterface
{
    /**
     * @var array
     */
    protected $productAbstractCache = [];

    /**
     * @var array
     */
    protected $productConcreteCache = [];

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface $productFacade
     */
    public function __construct(
        PriceProductScheduleToProductFacadeInterface $productFacade
    ) {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku(string $sku): ?int
    {
        if (isset($this->productAbstractCache[$sku])) {
            return $this->productAbstractCache[$sku];
        }

        $productAbstractId = $this->productFacade->findProductAbstractIdBySku($sku);

        if ($productAbstractId === null) {
            return null;
        }

        $this->productAbstractCache[$sku] = $productAbstractId;

        return $this->productAbstractCache[$sku];
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku(string $sku): ?int
    {
        if (isset($this->productConcreteCache[$sku])) {
            return $this->productConcreteCache[$sku];
        }

        $productConcreteId = $this->productFacade->findProductConcreteIdBySku($sku);

        if ($productConcreteId === null) {
            return null;
        }

        $this->productConcreteCache[$sku] = $productConcreteId;

        return $this->productConcreteCache[$sku];
    }
}
