<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductFormAdd extends AbstractType
{

    const FIELD_DESCRIPTION = 'description';
    const FIELD_NAME = 'name';
    const FIELD_SKU = 'sku';

    const OPTION_PRODUCT_ATTRIBUTES = 'option_product_attributes';
    const LOCALIZED_ATTRIBUTES = 'localized_attributes';
    const ATTRIBUTES = 'attributes';

    const VALIDATION_GROUP_ATTRIBUTES = 'validation_group_attributes';

    /**
     * @return string
     */
    public function getName()
    {
        return 'productAdd';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::ATTRIBUTES);

        $resolver->setDefaults([
            'cascade_validation' => true,
            'required' => false,
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
            ->addSkuField($builder)
            ->addLocalizedForm($builder)
            ->addAttributesForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SKU, 'text', [
                'label' => 'SKU',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedForm(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::LOCALIZED_ATTRIBUTES, 'collection', [
                'type' => new ProductLocalizedForm()
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributesForm(FormBuilderInterface $builder, $options)
    {
        $builder
            ->add(self::ATTRIBUTES, 'collection', [
                'type' => new ProductFormAttributes(
                    $options[self::ATTRIBUTES],
                    [self::VALIDATION_GROUP_ATTRIBUTES]
                )
            ]);

        return $this;
    }

}
