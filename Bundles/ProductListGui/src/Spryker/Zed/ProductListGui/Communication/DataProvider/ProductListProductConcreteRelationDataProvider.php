<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\DataProvider;

use Spryker\Zed\ProductListGui\Business\ProductListGuiFacadeInterface;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListProductConcreteRelationType;

class ProductListProductConcreteRelationDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Business\ProductListGuiFacadeInterface
     */
    protected $facade;

    /**
     * ProductListProductConcreteRelationDataProvider constructor.
     *
     * @param \Spryker\Zed\ProductListGui\Business\ProductListGuiFacadeInterface $facade
     */
    public function __construct(
        ProductListGuiFacadeInterface $facade
    ) {
        $this->facade = $facade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            ProductListProductConcreteRelationType::OPTION_PRODUCT_NAMES => $this->getCategoryList(),
        ];
    }

    /**
     * @return array
     */
    protected function getCategoryList(): array
    {
        return array_flip($this->facade->getAllProductsNames());
    }
}
