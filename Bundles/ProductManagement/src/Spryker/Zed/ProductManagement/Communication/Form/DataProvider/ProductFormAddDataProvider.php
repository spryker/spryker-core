<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

class ProductFormAddDataProvider extends AbstractProductFormDataProvider
{
    /**
     * @param array|null $priceDimension
     *
     * @return array
     */
    public function getData(?array $priceDimension = null)
    {
        return $this->getDefaultFormFields($priceDimension);
    }
}
