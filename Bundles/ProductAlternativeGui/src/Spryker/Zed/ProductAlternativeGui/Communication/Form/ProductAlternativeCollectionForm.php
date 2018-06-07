<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductAlternativeCollectionForm extends AbstractType
{
    public const FIELD_ID_PRODUCT = 'idProduct';
    public const FIELD_SUGGEST = 'suggest';

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'product_alternative_gui';
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
            ->addIdProductField($builder)
            ->addSuggestField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_PRODUCT, NumberType::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSuggestField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_SUGGEST, TextType::class, [
            'label' => false,
        ]);

        return $this;
    }
}
