<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Library\Collection\Collection;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeInputManager;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\AbstractProductFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;

class AttributeSuperForm extends AttributeAbstractForm
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options = [])
    {
        $name = $builder->getName();
        $attributes = $options[self::OPTION_ATTRIBUTE];
        $attributeData = new Collection($attributes[$name]);

        $inputManager = new AttributeInputManager();
        $allowInput = $attributeData->get(AbstractProductFormDataProvider::FORM_FIELD_ALLOW_INPUT);
        $isSuper = $attributeData->get(AbstractProductFormDataProvider::FORM_FIELD_SUPER);
        $isDisabled = $attributeData->get(AbstractProductFormDataProvider::FORM_FIELD_NAME_DISABLED);
        $value = $attributeData->get(AbstractProductFormDataProvider::FORM_FIELD_VALUE);
        $config = $this->getValueFieldConfig($name, $attributes);

        $config['attr']['data-value'] = null;

        $idLocale = $this->localeProvider->getCurrentLocale()->getIdLocale();
        if ($this->localeTransfer instanceof LocaleTransfer) {
            $idLocale = $this->localeTransfer->getIdLocale();
        }

        $existingValue = $this->productManagementQueryContainer
            ->queryFindAttributeByValueOrTranslation(
                $attributeData->get(AbstractProductFormDataProvider::FORM_FIELD_ID),
                $idLocale,
                $value
            )->findOne();

        $input = new Select2ComboBoxType();
        $config['multiple'] = true;
        $config['placeholder'] = '-';
        $config['choices'] = $this->getChoiceList($name, $attributes[$name], $existingValue, $idLocale);
        $config['attr']['tags'] = false;

        if ($allowInput) {
            //$config['attr']['tags'] = true;
        } else {
            //$config['attr']['class'] .= ' ajax';
        }

        if ($isDisabled) {
            $config = $this->getValueFieldConfig($name, $attributes);
            $config['read_only'] = true;
            $input = $inputManager->getSymfonyInputType(null, $value);
        }

        $builder->add(self::FIELD_VALUE, $input, $config);

        return $this;
    }

}
