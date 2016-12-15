<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;

class StockSubForm extends AbstractType
{

    const FIELD_QUANTITY = 'quantity';
    const FIELD_STOCK_TYPE = 'stockType';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addQuantityField($builder)
            ->addStockTypeField($builder);
    }

    /**
     * @return string The name of this type
     */
    public function getName()
    {
        return 'stock_form';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_QUANTITY, 'text', [
            'label' => 'Stock',
            'constraints' => [
                new Required(),
                new Regex(['pattern' => '/[\d]+/']),
            ]
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStockTypeField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_STOCK_TYPE, 'text', [
            'label' => 'Stock Type',
            'disabled' => true
        ]);

        return $this;
    }

}
