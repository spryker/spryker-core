<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;
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
        $formData = $this->getDefaultFormFields();

        $productAbstractTransfer = $this->productManagementFacade->getProductAbstractById($idProductAbstract);
        if ($productAbstractTransfer) {
            $formData = $productAbstractTransfer->toArray(true);
            $formData[ProductFormAdd::LOCALIZED_ATTRIBUTES] = $this->getLocalizedAbstractAttributes($productAbstractTransfer);

            $priceTransfer = $this->priceFacade->getProductAbstractPrice($idProductAbstract);
            if ($priceTransfer) {
                $formData[ProductFormAdd::PRICE_AND_STOCK][ProductFormPrice::FIELD_PRICE] = $priceTransfer->getPrice();
                $formData[ProductFormAdd::PRICE_AND_STOCK][ProductFormPrice::FIELD_TAX_RATE] = $productAbstractTransfer->getTaxSetId();
                $formData[ProductFormAdd::PRICE_AND_STOCK][ProductFormPrice::FIELD_STOCK] = $productAbstractTransfer->getTaxSetId();
            }
        }

        //TODO load from db when columsn are added
        $seoData = [];
        foreach ($formData[ProductFormAdd::LOCALIZED_ATTRIBUTES] as $locale => $localizedSeoData) {
            $seoData[$locale][ProductFormSeo::FIELD_META_TITLE] = $localizedSeoData[ProductFormSeo::FIELD_META_TITLE];
            $seoData[$locale][ProductFormSeo::FIELD_META_KEYWORDS] = $localizedSeoData[ProductFormSeo::FIELD_META_KEYWORDS];
            $seoData[$locale][ProductFormSeo::FIELD_META_DESCRIPTION] = $localizedSeoData[ProductFormSeo::FIELD_META_DESCRIPTION];

            unset($formData[ProductFormAdd::LOCALIZED_ATTRIBUTES][$locale][ProductFormSeo::FIELD_META_TITLE]);
            unset($formData[ProductFormAdd::LOCALIZED_ATTRIBUTES][$locale][ProductFormSeo::FIELD_META_KEYWORDS]);
            unset($formData[ProductFormAdd::LOCALIZED_ATTRIBUTES][$locale][ProductFormSeo::FIELD_META_DESCRIPTION]);
        }
        $formData[ProductFormAdd::SEO] = $seoData;

        $attributes = $this->getAttributesForAbstractProduct($idProductAbstract);
        $attributeMetadataCollection = $this->convertSelectedAttributeMetadataToFormValues($attributes);
        $attributeValueCollection = $this->convertSelectedAttributeValuesToFormValues($attributes);

        $formData[ProductFormAdd::ATTRIBUTE_METADATA] = $attributeMetadataCollection;
        $formData[ProductFormAdd::ATTRIBUTE_VALUES] = $attributeValueCollection;

        sd($formData);

        return $formData;
    }

}
