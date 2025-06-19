<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\StockGui\Communication\StockGuiCommunicationFactory getFactory()
 */
class UpdateStockForm extends StockForm
{
    /**
     * @var string
     */
    protected const FIELD_SHOULD_UPDATE_STOCK_RELATIONS_ASYNC = 'shouldUpdateStockRelationsAsync';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $this->addShouldUpdateStockRelationsAsyncField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addShouldUpdateStockRelationsAsyncField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SHOULD_UPDATE_STOCK_RELATIONS_ASYNC, CheckboxType::class, [
            'label' => 'Update stock relations asynchronously (recommended for big amount of products)',
            'required' => false,
        ]);

        return $this;
    }
}
