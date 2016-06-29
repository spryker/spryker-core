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
            $formData[ProductFormAdd::SEO] = $formData[ProductFormAdd::LOCALIZED_ATTRIBUTES];
        }

        //TODO load from db when columsn are added
        foreach ($formData[ProductFormAdd::SEO] as $locale => $localizedSeoData) {
            unset($formData[ProductFormAdd::SEO][$locale]['name']);
            unset($formData[ProductFormAdd::SEO][$locale]['attributes']);

            unset($formData[ProductFormAdd::LOCALIZED_ATTRIBUTES][$locale]['meta_title']);
            unset($formData[ProductFormAdd::LOCALIZED_ATTRIBUTES][$locale]['meta_keyword']);
            unset($formData[ProductFormAdd::LOCALIZED_ATTRIBUTES][$locale]['meta_description']);

            $formData[ProductFormAdd::SEO][$locale]['meta_title'] = '';
            $formData[ProductFormAdd::SEO][$locale]['meta_keyword'] = '';
            $formData[ProductFormAdd::SEO][$locale]['meta_description'] = '';
        }

        $attributes = $this->getAttributesForAbstractProduct($idProductAbstract);
        $attributeGroups = $this->convertSelectedAttributeGroupsToFormValues($attributes);
        $attributeValueCollection = $this->convertSelectedAttributeValuesToFormValues($attributes);

        $formData[ProductFormAdd::ATTRIBUTE_GROUP] = $attributeGroups;
        $formData[ProductFormAdd::ATTRIBUTE_VALUES] = $attributeValueCollection;
        $formData[ProductFormAdd::PRICE_AND_STOCK] = [
            'value' => [22]
        ];

        $formData = array_merge($defaults, $formData);

        return $formData;
    }

}
