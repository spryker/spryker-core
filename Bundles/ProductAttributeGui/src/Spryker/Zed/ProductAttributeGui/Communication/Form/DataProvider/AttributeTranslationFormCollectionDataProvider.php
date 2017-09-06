<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeTranslationCollectionForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeTranslationForm;
use Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleInterface;
use Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToProductAttributeInterface;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;

class AttributeTranslationFormCollectionDataProvider
{

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToProductAttributeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToProductAttributeInterface $productAttributeFacade
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductAttributeGuiToProductAttributeInterface $productAttributeFacade,
        ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer,
        ProductAttributeGuiToLocaleInterface $localeFacade
    ) {
        $this->productAttributeQueryContainer = $productAttributeQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->productAttributeFacade = $productAttributeFacade;
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
        $attributeEntity = $this->productAttributeQueryContainer
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
     * @return string|null
     */
    protected function getAttributeKeyTranslation($attributeKey, LocaleTransfer $localeTransfer)
    {
        $localizedAttributeKey = $this->productAttributeFacade
            ->findAttributeTranslationByKey($attributeKey, $localeTransfer);

        if (!$localizedAttributeKey) {
            return null;
        }

        return $localizedAttributeKey->getKeyTranslation();
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return bool
     */
    protected function getTranslateValues($idProductManagementAttribute)
    {
        $translationCount = $this->productAttributeQueryContainer
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
        $attributeValueEntities = $this->productAttributeQueryContainer
            ->queryProductManagementAttributeValueWithTranslation($idProductManagementAttribute, $idLocale)
            ->find()
            ->toArray();

        return $attributeValueEntities;
    }

}
