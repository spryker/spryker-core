<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductFormAttributes extends AbstractType
{

    const FIELD_VALUE = 'value';

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $validationGroups = [];

    /**
     * @param array $attributes
     * @param array $validationGroups
     */
    public function __construct(array $attributes = [], array $validationGroups = [])
    {
        $this->attributes = $attributes;
        $this->validationGroups = $validationGroups;
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
            'validation_groups' => $this->validationGroups
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
            'multiple' => true,
        ]);

        return $this;
    }

}
