<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Form;

use Generated\Shared\Transfer\StockProductTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AvailabilityStockForm extends AbstractType
{

    const FIELD_STOCKS = 'stocks';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addStock($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStock(FormBuilderInterface $builder)
    {
        $this->addStockField($builder);

        return $this;
    }

    /**
     * @return string The name of this type
     */
    public function getName()
    {
        return 'AvailabilityGui_stock';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStockField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_STOCKS, 'collection', [
            'type' => new StockSubForm(),
            'options' => [
                'data_class' => StockProductTransfer::class
            ]
        ]);

        return $this;
    }

}
