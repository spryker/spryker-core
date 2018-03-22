<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

class ProductFormAddDataProvider extends AbstractProductFormDataProvider
{
    /**
     * @return array
     */
    public function getData()
    {
        $formData = [];
        $defaults = $this->getDefaultFormFields();

        $formData = array_merge($defaults, $formData);

        return $formData;
    }
}
