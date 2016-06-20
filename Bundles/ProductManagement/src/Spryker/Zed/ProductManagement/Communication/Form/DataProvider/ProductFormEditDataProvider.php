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
     * @param int $idProductAbstract
     * @param array $attributeCollection
     *
     * @return array
     */
    public function getData($idProductAbstract, array $attributeCollection)
    {
        $formData = [];
        $defaults = $this->getDefaultFormFields($attributeCollection);

        $productAbstractTransfer = $this->productManagementFacade->getProductAbstractById($idProductAbstract);
        if ($productAbstractTransfer) {
            $formData = $productAbstractTransfer->toArray(true);
            $formData[ProductFormAdd::LOCALIZED_ATTRIBUTES] = $this->getLocalizedAbstractAttributes($productAbstractTransfer);
        }

        $formData[ProductFormAdd::ATTRIBUTES] = $this->getAttributes($attributeCollection);

        $formData = array_merge($defaults, $formData);
        
        dump($formData);

        return $formData;
    }

    /**
     * @param array $attributeCollection
     *
     * @return array
     */
    protected function getDefaultFormFields(array $attributeCollection)
    {
        return [
            ProductFormAdd::FIELD_SKU => null,
            ProductFormAdd::LOCALIZED_ATTRIBUTES => $this->getLocalizedAttributesDefaultFields($attributeCollection),
            ProductFormAdd::ATTRIBUTES => $this->getAttributesDefaultFields($attributeCollection)
        ];
    }

}
