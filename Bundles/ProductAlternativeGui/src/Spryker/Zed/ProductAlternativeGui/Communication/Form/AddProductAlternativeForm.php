<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddProductAlternativeForm extends AbstractType
{
    public const FIELD_PRODUCT_ALTERNATIVE = 'alternative_products';

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
        $this->addProductAlternativeSearchField($builder);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, $this->getProductAlternativeSearchPreSubmitCallback());
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductAlternativeSearchField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_PRODUCT_ALTERNATIVE, SelectType::class, [
            'label' => 'Add Product Alternative by Name or SKU',
            'attr' => [
                'placeholder' => 'Type three letters of name or sku for suggestions.',
            ],
            'required' => false,
            'choices' => [],
            'multiple' => true,
        ]);

        return $this;
    }

    /**
     * @return \Closure
     */
    protected function getProductAlternativeSearchPreSubmitCallback(): callable
    {
        return function (FormEvent $e) {
            $data = $e->getData();
            $form = $e->getForm();
            if (empty($data[static::FIELD_PRODUCT_ALTERNATIVE])) {
                return;
            }
            if ($form->has(static::FIELD_PRODUCT_ALTERNATIVE)) {
                $form->remove(static::FIELD_PRODUCT_ALTERNATIVE);
            }
            $form->add(
                static::FIELD_PRODUCT_ALTERNATIVE,
                SelectType::class,
                [
                    'label' => 'Add Product Alternative by Name or SKU',
                    'attr' => [
                        'placeholder' => 'Type three letters of name or sku for suggestions.',
                    ],
                    'required' => false,
                    'choices' => $data[static::FIELD_PRODUCT_ALTERNATIVE],
                    'multiple' => true,
                ]
            );
        };
    }
}
