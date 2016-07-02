<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormGeneral;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormPrice;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormSeo;

class ProductFormEditDataProvider extends AbstractProductFormDataProvider
{

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getData($idProductAbstract)
    {
        $defaults = $this->getDefaultFormFields();

        $productAbstractTransfer = $this->productManagementFacade->getProductAbstractById($idProductAbstract);
        if ($productAbstractTransfer) {
            $formData = $productAbstractTransfer->toArray(true);
            $formData[ProductFormAdd::LOCALIZED_ATTRIBUTES] = $this->getLocalizedAbstractAttributes($productAbstractTransfer);
            $formData[ProductFormAdd::SEO] = $formData[ProductFormAdd::LOCALIZED_ATTRIBUTES];

            $priceTransfer = $this->priceFacade->getProductAbstractPrice($idProductAbstract);
            if ($priceTransfer) {
                $formData[ProductFormAdd::PRICE_AND_STOCK][ProductFormPrice::FIELD_PRICE] = $priceTransfer->getPrice();
            }
        }

        $formData[ProductFormAdd::PRICE_AND_STOCK][ProductFormPrice::FIELD_TAX_RATE] = $productAbstractTransfer->getTaxSetId();
        $formData[ProductFormAdd::PRICE_AND_STOCK][ProductFormPrice::FIELD_STOCK] = $productAbstractTransfer->getTaxSetId();

        //TODO load from db when columsn are added
        foreach ($formData[ProductFormAdd::SEO] as $locale => $localizedSeoData) {
            unset($formData[ProductFormAdd::SEO][$locale][ProductFormGeneral::FIELD_NAME]);
            unset($formData[ProductFormAdd::SEO][$locale][ProductFormSeo::ATTRIBUTES]);

            unset($formData[ProductFormAdd::LOCALIZED_ATTRIBUTES][$locale][ProductFormSeo::FIELD_META_TITLE]);
            unset($formData[ProductFormAdd::LOCALIZED_ATTRIBUTES][$locale][ProductFormSeo::FIELD_META_KEYWORD]);
            unset($formData[ProductFormAdd::LOCALIZED_ATTRIBUTES][$locale][ProductFormSeo::FIELD_META_DESCRIPTION]);

            $formData[ProductFormAdd::SEO][$locale][ProductFormSeo::FIELD_META_TITLE] = '';
            $formData[ProductFormAdd::SEO][$locale][ProductFormSeo::FIELD_META_KEYWORD] = '';
            $formData[ProductFormAdd::SEO][$locale][ProductFormSeo::FIELD_META_DESCRIPTION] = '';
        }

        $attributes = $this->getAttributesForAbstractProduct($idProductAbstract);
        $attributeMetadataCollection = $this->convertSelectedAttributeMetadataToFormValues($attributes);
        $attributeValueCollection = $this->convertSelectedAttributeValuesToFormValues($attributes);
        sd($attributes, $attributeMetadataCollection, $attributeValueCollection);

        $formData[ProductFormAdd::ATTRIBUTE_METADATA] = $attributeMetadataCollection;
        $formData[ProductFormAdd::ATTRIBUTE_VALUES] = $attributeValueCollection;

        $formData = array_merge($defaults, $formData);

        sd($formData);

        return $formData;
    }

}
