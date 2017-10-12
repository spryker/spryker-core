<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class BundledProductForm extends AbstractType
{
    const FIELD_QUANTITY = 'quantity';
    const FIELD_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    const FIELD_SKU = 'sku';
    const NUMERIC_PATTERN = '/\d+/';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addNameField($builder)
            ->addQuantityField($builder)
            ->addIdProductConcreteField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_SKU, 'text', [
            'label' => 'sku',
            'required' => true,
            'attr' => [
                'readonly' => 'readonly',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_QUANTITY, 'text', [
            'label' => 'quantity',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => self::NUMERIC_PATTERN,
                    'message' => 'Invalid quantity provided. Valid values "0-9".',
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductConcreteField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_PRODUCT_CONCRETE, 'hidden', [
            'label' => 'quantity',
            'required' => false,
            'constraints' => [],
        ]);

        return $this;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'bundled_product';
    }
}
