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

class ProductFormAttributeGroup extends AbstractType
{

    const FIELD_VALUE = 'value';

    /**
     * @var array
     */
    protected $attributeGroups;

    /**
     * @var string
     */
    protected $validationGroup;

    /**
     * @param array $attributeGroups
     * @param array $validationGroup
     */
    public function __construct(array $attributeGroups, $validationGroup)
    {
        $this->attributeGroups = $attributeGroups;
        $this->validationGroup = $validationGroup;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormAttributeGroup';
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
            'label' => $this->attributeGroups[$builder->getName()],
        ]);

        return $this;
    }

}
