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
     *
     * @return array
     */
    public function getData($idProductAbstract)
    {
        $formData = [];
        $defaults = $this->getDefaultFormFields();

        $productAbstractTransfer = $this->productManagementFacade->getProductAbstractById($idProductAbstract);
        if ($productAbstractTransfer) {
            $formData = $productAbstractTransfer->toArray(true);
            $formData[ProductFormAdd::LOCALIZED_ATTRIBUTES] = $this->getLocalizedAbstractAttributes($productAbstractTransfer);
        }

        $attributeGroupCollection = [];
        $attributes = $this->getAttributesForAbstractProduct($idProductAbstract);
        foreach ($attributes as $type => $valueSet) {
            $attributeGroupCollection[$type]['value'] = true;
        }

        $formData[ProductFormAdd::ATTRIBUTE_GROUP] = $attributeGroupCollection;
        //$formData[ProductFormAdd::ATTRIBUTE_GROUP] = array_merge($defaults[ProductFormAdd::ATTRIBUTE_GROUP], $formData[ProductFormAdd::ATTRIBUTE_GROUP]);
        //$formData[ProductFormAdd::ATTRIBUTE_VALUES] = $this->convertAttributesToFormValues($this->getAttributeValues($idProductAbstract));

        $formData = array_merge($defaults, $formData);


        return $formData;
    }

}
