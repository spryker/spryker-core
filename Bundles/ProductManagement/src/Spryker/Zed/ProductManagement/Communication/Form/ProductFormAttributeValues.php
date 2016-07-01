<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductManagement\Communication\Form\Constraints\AttributeFieldNotBlank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductFormAttributeValues extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_VALUE = 'value';

    /**
     * @var array
     */
    protected $attributeValues;

    /**
     * @var array
     */
    protected $attributeMetadataCollection;

    /**
     * @var string
     */
    protected $validationGroup;

    /**
     * @param array $attributes
     * @param string $validationGroup
     */
    public function __construct(array $attributes, $validationGroup)
    {
        $this->attributeValues = $attributes[ProductFormAdd::ATTRIBUTE_VALUES];
        $this->attributeMetadataCollection = $attributes[ProductFormAdd::ATTRIBUTE_METADATA];
        $this->validationGroup = $validationGroup;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'productAttributeValues';
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
        if (isset($this->attributeMetadataCollection[$builder->getName()])) {
            $name = $this->attributeMetadataCollection[$builder->getName()];
        }

        $builder
            ->add(self::FIELD_NAME, 'checkbox', [
                'label' => $name
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options)
    {
        $name = $builder->getName();
        if (isset($this->attributeMetadataCollection[$builder->getName()])) {
            $name = $this->attributeMetadataCollection[$builder->getName()];
        }

        $choices = [];
        if (isset($this->attributeValues[$builder->getName()])) {
            $choices = $this->attributeValues[$builder->getName()];
        }

        $builder->add(self::FIELD_VALUE, new Select2ComboBoxType(), [
            'choices' => $choices,
            'multiple' => true,
            'label' => false,
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
