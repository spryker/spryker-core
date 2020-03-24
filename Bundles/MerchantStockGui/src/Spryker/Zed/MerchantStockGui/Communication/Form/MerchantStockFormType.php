<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStockGui\Communication\Form;

use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantStockGui\Communication\MerchantStockGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantStockGui\MerchantStockGuiConfig getConfig()
 */
class MerchantStockFormType extends AbstractType
{
    protected const FIELD_STOCKS = 'stocks';
    protected const LABEL_STOCKS = 'Warehouses';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$builder->getData()->getStocks()->count()) {
            return;
        }

        $this->addIdMerchantStockField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIdMerchantStockField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_STOCKS, Select2ComboBoxType::class, [
            'choices' => $options[static::FIELD_STOCKS],
            'choice_value' => function (StockTransfer $stockTransfer) {
                return $stockTransfer->getIdStock();
            },
            'choice_label' => StockTransfer::NAME,
            'data' => $this->getFactory()
                ->createMerchantStockFormDataProvider()
                ->getData($builder->getData()),
            'required' => false,
            'multiple' => true,
            'label' => static::LABEL_STOCKS,
            'disabled' => true,
        ]);

        return $this;
    }
}
