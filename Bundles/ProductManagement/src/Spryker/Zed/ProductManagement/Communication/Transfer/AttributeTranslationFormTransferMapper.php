<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Transfer;

use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Spryker\Zed\ProductManagement\Communication\Form\Attribute\AttributeTranslationForm;
use Spryker\Zed\ProductManagement\Communication\Form\Attribute\AttributeValueTranslationForm;
use Symfony\Component\Form\FormInterface;

class AttributeTranslationFormTransferMapper implements AttributeTranslationFormTransferMapperInterface
{

    /**
     * @param \Symfony\Component\Form\FormInterface $translationForm
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function createTransfer(FormInterface $translationForm)
    {
        $attributeTransfer = new ProductManagementAttributeTransfer();
        $translationFormData = $translationForm->getData();

        if (empty($translationFormData)) {
            return $attributeTransfer;
        }

        $firstTranslationFormData = current($translationFormData);

        $attributeTransfer = $this->setGeneralAttributeData($attributeTransfer, $firstTranslationFormData);
        $attributeTransfer = $this->setLocalizedKeyData($attributeTransfer, $translationFormData);
        $attributeTransfer = $this->setAttributeValues($attributeTransfer, $firstTranslationFormData, $translationFormData);

        return $attributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     * @param array $firstTranslationFormData
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function setGeneralAttributeData(ProductManagementAttributeTransfer $attributeTransfer, array $firstTranslationFormData)
    {
        $attributeTransfer
            ->setIdProductManagementAttribute($firstTranslationFormData[AttributeTranslationForm::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE])
            ->setKey($firstTranslationFormData[AttributeTranslationForm::FIELD_KEY]);

        return $attributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     * @param array $translationFormData
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function setLocalizedKeyData(ProductManagementAttributeTransfer $attributeTransfer, array $translationFormData)
    {
        foreach ($translationFormData as $locale => $translateFormData) {
            $localizedKeyTransfer = new LocalizedProductManagementAttributeKeyTransfer();
            $localizedKeyTransfer
                ->setLocaleName($locale)
                ->setKeyTranslation($translateFormData[AttributeTranslationForm::FIELD_KEY_TRANSLATION]);

            $attributeTransfer->addLocalizedKey($localizedKeyTransfer);
        }

        return $attributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     * @param array $firstTranslationFormData
     * @param array $translationFormData
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function setAttributeValues(ProductManagementAttributeTransfer $attributeTransfer, array $firstTranslationFormData, array $translationFormData)
    {
        foreach ($firstTranslationFormData[AttributeTranslationForm::FIELD_VALUE_TRANSLATIONS] as $index => $valueTranslationData) {
            $attributeValueTransfer = new ProductManagementAttributeValueTransfer();
            $attributeValueTransfer
                ->setIdProductManagementAttributeValue($valueTranslationData[AttributeValueTranslationForm::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE])
                ->setValue($valueTranslationData[AttributeValueTranslationForm::FIELD_VALUE]);

            if ($firstTranslationFormData[AttributeTranslationForm::FIELD_TRANSLATE_VALUES]) {
                $attributeValueTransfer = $this->setLocalizedAttributeValues($attributeValueTransfer, $translationFormData, $index);
            }

            $attributeTransfer->addValue($attributeValueTransfer);
        }

        return $attributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer $attributeValueTransfer
     * @param array $translationFormData
     * @param int $index
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer
     */
    protected function setLocalizedAttributeValues(ProductManagementAttributeValueTransfer $attributeValueTransfer, array $translationFormData, $index)
    {
        foreach ($translationFormData as $translateFormData) {
            $localizedValueData = $translateFormData[AttributeTranslationForm::FIELD_VALUE_TRANSLATIONS][$index];

            $attributeValueTranslationTransfer = new ProductManagementAttributeValueTranslationTransfer();
            $attributeValueTranslationTransfer->fromArray($localizedValueData, true);
            $attributeValueTransfer->addLocalizedValue($attributeValueTranslationTransfer);
        }

        return $attributeValueTransfer;
    }

}
