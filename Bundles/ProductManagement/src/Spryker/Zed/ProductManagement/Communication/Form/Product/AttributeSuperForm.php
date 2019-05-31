<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Everon\Component\Collection\Collection;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeInputManager;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\AbstractProductFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
class AttributeSuperForm extends AttributeAbstractForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options = [])
    {
        $name = $builder->getName();
        $attributes = $options[self::OPTION_ATTRIBUTE];
        $attributeData = new Collection($attributes[$name]);

        $inputManager = new AttributeInputManager();
        $isDisabled = $attributeData->get(AbstractProductFormDataProvider::FORM_FIELD_NAME_DISABLED);
        $value = $attributeData->get(AbstractProductFormDataProvider::FORM_FIELD_VALUE);
        $config = $this->getValueFieldConfig($name, $attributes);

        $config['attr']['data-value'] = null;

        $idLocale = $this->getFactory()->createLocaleProvider()->getCurrentLocale()->getIdLocale();
        if ($this->localeTransfer instanceof LocaleTransfer) {
            $idLocale = $this->localeTransfer->getIdLocale();
        }

        $existingValue = $this->getQueryContainer()
            ->queryFindAttributeByValueOrTranslation(
                $attributeData->get(AbstractProductFormDataProvider::FORM_FIELD_ID),
                $idLocale,
                $value
            )->findOne();

        $input = Select2ComboBoxType::class;
        $config['multiple'] = true;
        $config['placeholder'] = '-';
        $config['choices'] = array_flip($this->getChoiceList($name, $attributes[$name], $existingValue, $idLocale));

        $config['attr']['tags'] = false;

        if ($isDisabled) {
            $config = $this->getValueFieldConfig($name, $attributes);
            $config['attr']['readonly'] = 'readonly';
            $input = $inputManager->getSymfonyInputType(null, $value);
        }

        $builder->add(self::FIELD_VALUE, $input, $config);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'product_attribute_super';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
