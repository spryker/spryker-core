<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Communication\Form\Attribute\AttributeTranslationCollectionForm;
use Spryker\Zed\ProductManagement\Communication\Form\Attribute\AttributeTranslationForm;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeTranslationFormCollectionDataProvider
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface $glossaryFacade
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToLocaleInterface $localeFacade,
        ProductManagementToGlossaryInterface $glossaryFacade
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return array
     */
    public function getData($idProductManagementAttribute)
    {
        return [
            AttributeTranslationCollectionForm::FIELD_TRANSLATIONS => $this->getTranslationFields($idProductManagementAttribute),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return array
     */
    protected function getTranslationFields($idProductManagementAttribute)
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();

        $attributeKey = $this->getAttributeKey($idProductManagementAttribute);

        $fields = [];
        foreach ($availableLocales as $localeTransfer) {
            $fields[$localeTransfer->getLocaleName()] = [
                AttributeTranslationForm::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE => $idProductManagementAttribute,
                AttributeTranslationForm::FIELD_KEY => $attributeKey,
                AttributeTranslationForm::FIELD_KEY_TRANSLATION => $this->getAttributeKeyTranslation($attributeKey, $localeTransfer),
                AttributeTranslationForm::FIELD_TRANSLATE_VALUES => $this->getTranslateValues($idProductManagementAttribute),
                AttributeTranslationForm::FIELD_VALUE_TRANSLATIONS => $this->getValueTranslations($idProductManagementAttribute, $localeTransfer->getIdLocale()),
            ];
        }

        return $fields;
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return string
     */
    protected function getAttributeKey($idProductManagementAttribute)
    {
        $attributeEntity = $this->productManagementQueryContainer
            ->queryProductManagementAttribute()
            ->filterByIdProductManagementAttribute($idProductManagementAttribute)
            ->findOne();

        return $attributeEntity
            ->getSpyProductAttributeKey()
            ->getKey();
    }

    /**
     * @param string $attributeKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getAttributeKeyTranslation($attributeKey, LocaleTransfer $localeTransfer)
    {
        $glossaryKey = ProductManagementConstants::PRODUCT_MANAGEMENT_ATTRIBUTE_GLOSSARY_PREFIX . $attributeKey;

        if ($this->glossaryFacade->hasTranslation($glossaryKey, $localeTransfer)) {
            return $this->glossaryFacade
                ->getTranslation($glossaryKey, $localeTransfer)
                ->getValue();
        }

        return null;
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return bool
     */
    protected function getTranslateValues($idProductManagementAttribute)
    {
        $translationCount = $this->productManagementQueryContainer
            ->queryProductManagementAttributeValue()
            ->joinSpyProductManagementAttributeValueTranslation()
            ->filterByFkProductManagementAttribute($idProductManagementAttribute)
            ->count();

        return $translationCount > 0;
    }

    /**
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     *
     * @return array
     */
    protected function getValueTranslations($idProductManagementAttribute, $idLocale)
    {
        $attributeValueEntities = $this->productManagementQueryContainer
            ->queryProductManagementAttributeValueWithTranslation($idProductManagementAttribute, $idLocale)
            ->find()
            ->toArray();

        return $attributeValueEntities;
    }

}
