<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;

class ProductFormEditDataProvider extends AbstractProductFormDataProvider
{

    /**
     * @return array
     */
    public function getData()
    {
        $formData = [];
        $fields = $this->getDefaultFormFields();

        return array_merge($formData, $fields);
    }

    /**
     * @return array
     */
    protected function getDefaultFormFields()
    {
        return [
            ProductFormAdd::FIELD_SKU => null,
            ProductFormAdd::LOCALIZED_ATTRIBUTES => $this->getAttributesDefaultFields()
        ];
    }

}
