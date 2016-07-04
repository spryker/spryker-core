<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductFormAttributeMetadata extends AbstractType
{

    const FIELD_VALUE = 'value';

    const OPTION_LABELS = 'option_labels';
    const OPTION_VALUES = 'option_values';
    const LABEL = 'label';

    /**
     * @var array
     */
    protected $attributeLabels;

    /**
     * @var array
     */
    protected $attributeValues;

    /**
     * @var string
     */
    protected $validationGroup;

    /**
     * @param array $attributeLabels
     * @param array $attributeValues
     * @param array $validationGroup
     */
    public function __construct(array $attributeLabels, array $attributeValues, $validationGroup)
    {
        $this->attributeLabels = $attributeLabels;
        $this->attributeValues = $attributeValues;
        $this->validationGroup = $validationGroup;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormAttributeMetadata';
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
            ->addValueField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options = [])
    {

        $builder->add(self::FIELD_VALUE, new CheckboxType(), [
            'label' => $builder->getName(),
            'value' => false //$this->attributeValues[$name][self::FIELD_VALUE],
        ]);

        return $this;

        //sd($this->attributeLabels, $this->attributeValues);

        $name = $builder->getName();
        $label = $name;

        if (isset($this->attributeLabels[$name])) {
            $label = $this->attributeLabels[$name][self::LABEL];
        }

        $isDisabled = !array_key_exists($name, $this->attributeValues);

        $builder->add(self::FIELD_VALUE, new CheckboxType(), [
            'disabled' => $isDisabled,
            'label' => $builder->getName(),
            'value' => false //$this->attributeValues[$name][self::FIELD_VALUE],
        ]);

        return $this;
    }

}
