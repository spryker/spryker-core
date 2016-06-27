<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraint;

class ProductFormAttributes extends AbstractType
{

    const FIELD_VALUE = 'value';

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $validationGroup;

    /**
     * @param array $attributes
     * @param array $validationGroup
     */
    public function __construct(array $attributes = [], $validationGroup)
    {
        $this->attributes = $attributes;
        $this->validationGroup = $validationGroup;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'productAttributes';
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
            ->addValueField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_VALUE, new Select2ComboBoxType(), [
            'label' => $builder->getName(),
            'choices' => $this->attributes[$builder->getName()],
            'multiple' => true
        ]);

        return $this;
    }

}
