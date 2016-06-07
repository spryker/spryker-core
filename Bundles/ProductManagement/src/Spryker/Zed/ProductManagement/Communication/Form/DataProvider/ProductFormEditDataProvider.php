<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

class ProductFormEditDataProvider extends AbstractProductFormDataProvider
{

    /**
     * @return array
     */
    public function getData()
    {
        $fields = $this->getDefaultFormFields();

        return $fields;
    }

    /**
     * @return array
     */
    protected function getDefaultFormFields()
    {
        return [
        ];
    }

}
