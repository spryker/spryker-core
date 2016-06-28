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

        $attributes = $this->getAttributesForAbstractProduct($idProductAbstract);
        $attributeGroups = $this->convertSelectedAttributeGroupsToFormValues($attributes);
        $attributeValueCollection = $this->convertSelectedAttributeValuesToFormValues($attributes);

        $formData[ProductFormAdd::ATTRIBUTE_GROUP] = $attributeGroups;
        $formData[ProductFormAdd::ATTRIBUTE_VALUES] = $attributeValueCollection;

        $formData = array_merge($defaults, $formData);

        return $formData;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function convertSelectedAttributeGroupsToFormValues(array $attributes)
    {
        $attributeGroupCollection = array_keys($attributes) + array_keys($this->attributeGroupCollection);

        $groupValues = [];
        foreach ($attributeGroupCollection as  $type) {
            $groupValues[$type]['value'] = array_key_exists($type, $attributes);
        }

        return $groupValues;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function convertSelectedAttributeValuesToFormValues(array $attributes)
    {
        $values = [];
        foreach ($attributes as $type => $valueSet) {
            $values[$type]['value'] = array_keys($valueSet);
        }

        foreach ($this->attributeValueCollection as $type => $valueSet) {
            if (!array_key_exists($type, $values)) {
                $values[$type]['value'] = [];
            }
        }

        return $values;
    }

}
