<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductFormAttributeValues extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_VALUE = 'value';

    const OPTION_LABELS = 'option_labels';
    const OPTION_VALUES = 'option_values';
    const LABEL = 'label';
    const MULTIPLE = 'multiple';
    const CUSTOM = 'custom';

    /**
     * @var array
     */
    protected $attributeValues;

    /**
     * @var string
     */
    protected $validationGroup;

    /**
     * @param array $attributeValues
     * @param array $validationGroup
     */
    public function __construct(array $attributeValues, $validationGroup)
    {
        $this->attributeValues = $attributeValues;
        $this->validationGroup = $validationGroup;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormAttributeValues';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'required' => false,
            'cascade_validation' => true,
            'validation_groups' => [$this->validationGroup]
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addCheckboxNameField($builder, $options)
            ->addValueField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCheckboxNameField(FormBuilderInterface $builder, array $options)
    {
        $name = $builder->getName();
        $label = $name;
        $isDisabled = true;

        if (isset($this->attributeValues[$name])) {
            $label = $this->attributeValues[$name][self::LABEL];
            $isDisabled = $this->attributeValues[$name][self::CUSTOM] === true;
        }

        $builder
            ->add(self::FIELD_NAME, 'checkbox', [
                'label' => $label,
                'disabled' => $isDisabled
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options = [])
    {
        $name = $builder->getName();
        $label = $name;
        $isDisabled = true;

        if (isset($this->attributeValues[$name])) {
            $label = $this->attributeValues[$name][self::LABEL];
            $isDisabled = $this->attributeValues[$name][self::CUSTOM] === true;
        }

        $builder
            ->add(self::FIELD_VALUE, 'text', [
                'label' => false,
                'disabled' => $isDisabled
            ]);

        return $this;

        $builder->add(self::FIELD_VALUE, new Select2ComboBoxType(), [ //TODO type depends on DB settings
            'disabled' => $isDisabled,
            'multiple' => true, //TODO depends on DB settings
            'label' => false,
            'choices' => [], // ['red' => 'red'],
            /*            'constraints' => [
                            new AttributeFieldNotBlank([
                                'attributeFieldValue' => self::FIELD_VALUE,
                                'attributeCheckboxFieldName' => self::FIELD_NAME,
                            ]),
                        ],*/
        ]);

        return $this;
    }

}
