<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Orm\Zed\ProductManagement\Persistence\Base\SpyProductManagementAttribute;
use Spryker\Zed\ProductManagement\Communication\Form\AttributeForm;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeFormDataProvider
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     */
    public function __construct(ProductManagementQueryContainerInterface $productManagementQueryContainer)
    {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
    }

    /**
     * @param int|null $idProductManagementAttribute
     *
     * @return array
     */
    public function getData($idProductManagementAttribute = null)
    {
        if ($idProductManagementAttribute === null) {
            return [];
        }

        $productManagementAttributeEntity = $this->getAttributeEntity($idProductManagementAttribute);

        return [
            AttributeForm::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE => $productManagementAttributeEntity->getIdProductManagementAttribute(),
            AttributeForm::FIELD_KEY => $productManagementAttributeEntity->getSpyProductAttributeKey()->getKey(),
            AttributeForm::FIELD_INPUT_TYPE => $productManagementAttributeEntity->getInputType(),
            AttributeForm::FIELD_ALLOW_INPUT => $productManagementAttributeEntity->getAllowInput(),
            AttributeForm::FIELD_IS_MULTIPLE => $productManagementAttributeEntity->getIsMultiple(),
            AttributeForm::FIELD_VALUES => array_keys($this->getValues($productManagementAttributeEntity)),
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
            AttributeForm::OPTION_ATTRIBUTE_TYPE_CHOICES => $this->getAttributeTypeChoices(),
        ];

        if ($idProductManagementAttribute === null) {
            return $options;
        }

        $productManagementAttributeEntity = $this->getAttributeEntity($idProductManagementAttribute);

        $options[AttributeForm::OPTION_VALUES_CHOICES] = $this->getValues($productManagementAttributeEntity);

        return $options;
    }

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\Base\SpyProductManagementAttribute $productManagementAttributeEntity
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
     * @return array
     */
    protected function getAttributeTypeChoices()
    {
        // TODO: need to come from config
        return [
            'text' => 'text',
            'textarea' => 'textarea',
            'number' => 'number',
            'float' => 'float',
            'date' => 'date',
            'time' => 'time',
            'datetime' => 'datetime',
            'select' => 'select',
        ];
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttribute|null
     */
    protected function getAttributeEntity($idProductManagementAttribute)
    {
        return $this->productManagementQueryContainer
            ->queryProductManagementAttribute()
            ->filterByIdProductManagementAttribute($idProductManagementAttribute)
            ->findOne();
    }

}
