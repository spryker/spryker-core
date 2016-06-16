<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductFormAdd extends AbstractType
{

    const FIELD_DESCRIPTION = 'description';
    const FIELD_NAME = 'name';
    const FIELD_SKU = 'sku';

    const LOCALIZED_ATTRIBUTES = 'localized_attributes';
    const ATTRIBUTES = 'attributes';

    /**
     * @return string
     */
    public function getName()
    {
        return 'productAdd';
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
            ->addLocalizedForm($builder);
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
    protected function addAttributesForm(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::ATTRIBUTES, 'collection', [
                'type' => new ProductAttributesForm()
            ]);

        return $this;
    }

}
