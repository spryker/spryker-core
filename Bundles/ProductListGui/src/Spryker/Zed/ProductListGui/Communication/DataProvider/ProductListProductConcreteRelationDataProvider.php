<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\DataProvider;

use Spryker\Zed\ProductListGui\Communication\Form\ProductListProductConcreteRelationType;

class ProductListProductConcreteRelationDataProvider
{
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
        return ['name A' => 1, 'B' => 2];
    }
}
