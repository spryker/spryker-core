<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider;

use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeForm;
use Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToProductAttributeInterface;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;

class AttributeFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToProductAttributeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToProductAttributeInterface $productAttributeFacade
     */
    public function __construct(
        ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer,
        ProductAttributeGuiToProductAttributeInterface $productAttributeFacade
    ) {
        $this->productAttributeQueryContainer = $productAttributeQueryContainer;
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param int|null $idProductManagementAttribute
     *
     * @return array
     */
    public function getData($idProductManagementAttribute = null)
    {
        if ($idProductManagementAttribute === null) {
            return [
                AttributeForm::FIELD_ALLOW_INPUT => false,
            ];
        }

        $productManagementAttributeEntity = $this->getAttributeEntity($idProductManagementAttribute);

        return [
            AttributeForm::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE => $productManagementAttributeEntity->getIdProductManagementAttribute(),
            AttributeForm::FIELD_KEY => $productManagementAttributeEntity->getSpyProductAttributeKey()->getKey(),
            AttributeForm::FIELD_INPUT_TYPE => $productManagementAttributeEntity->getInputType(),
            AttributeForm::FIELD_ALLOW_INPUT => $productManagementAttributeEntity->getAllowInput(),
            AttributeForm::FIELD_IS_SUPER => $productManagementAttributeEntity->getSpyProductAttributeKey()->getIsSuper(),
            AttributeForm::FIELD_VALUES => array_values($this->getValues($productManagementAttributeEntity)),
        ];
    }

    /**
     * @param int|null $idProductManagementAttribute
     *
     * @return array
     */
    public function getOptions($idProductManagementAttribute = null)
    {
        $options = [
            AttributeForm::OPTION_ATTRIBUTE_TYPE_CHOICES => $this->productAttributeFacade->getAttributeAvailableTypes(),
            AttributeForm::OPTION_VALUES_CHOICES => [],
        ];

        if ($idProductManagementAttribute === null) {
            return $options;
        }

        $productManagementAttributeEntity = $this->getAttributeEntity($idProductManagementAttribute);

        $options[AttributeForm::OPTION_IS_UPDATE] = true;
        $options[AttributeForm::OPTION_VALUES_CHOICES] = $this->getValues($productManagementAttributeEntity);

        return $options;
    }

    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute $productManagementAttributeEntity
     *
     * @return array
     */
    protected function getValues(SpyProductManagementAttribute $productManagementAttributeEntity)
    {
        $values = [];

        foreach ($productManagementAttributeEntity->getSpyProductManagementAttributeValues() as $attributeValueEntity) {
            $values[$attributeValueEntity->getValue()] = $attributeValueEntity->getValue();
        }

        return $values;
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute|null
     */
    protected function getAttributeEntity($idProductManagementAttribute)
    {
        return $this->productAttributeQueryContainer
            ->queryProductManagementAttribute()
            ->findOneByIdProductManagementAttribute($idProductManagementAttribute);
    }
}
