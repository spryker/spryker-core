<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Price;

use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class ProductMoneyCollectionType extends MoneyCollectionType
{
    /**
     * @var string
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var string
     */
    protected static $grossPriceModeIdentifier;

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'product_money_collection';
    }

    /**
     * @param \Symfony\Component\Form\FormView $formViewCollection
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function finishView(FormView $formViewCollection, FormInterface $form, array $options)
    {
        $grossPriceModeIdentifier = $this->getGrossPriceModeIdentifier();
        $netPriceModeIdentifier = $this->getNetPriceModeIdentifier();

        $priceTypes = [
            $grossPriceModeIdentifier => [],
            $netPriceModeIdentifier => [],
        ];

        $priceTable = [];
        foreach ($formViewCollection as $productMoneyTypeFormView) {
            $moneyValueFormView = $productMoneyTypeFormView['moneyValue'];

            /** @var \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer */
            $priceTypeTransfer = $productMoneyTypeFormView->vars['price_type'];

            $priceType = $priceTypeTransfer->getName();
            $priceModeConfiguration = $priceTypeTransfer->getPriceModeConfiguration();

            $storeName = $moneyValueFormView->vars['store_name'];
            $currencySymbol = $moneyValueFormView->vars['currency_symbol'];

            if ($priceModeConfiguration === ProductManagementConstants::PRICE_MODE_BOTH) {
                $priceTypes[$netPriceModeIdentifier][$priceType] = $priceTypeTransfer;
                $priceTypes[$grossPriceModeIdentifier][$priceType] = $priceTypeTransfer;

                $priceTable[$storeName][$currencySymbol][$netPriceModeIdentifier][$priceType] = $productMoneyTypeFormView;
                $priceTable[$storeName][$currencySymbol][$grossPriceModeIdentifier][$priceType] = $productMoneyTypeFormView;
            } else {
                if (!isset($priceTypes[$priceModeConfiguration][$priceType])) {
                    $priceTypes[$priceModeConfiguration][$priceType] = $priceTypeTransfer;
                }

                $priceTable[$storeName][$currencySymbol][$priceModeConfiguration][$priceType] = $productMoneyTypeFormView;
            }
        }

        $formViewCollection->vars['priceTable'] = $priceTable;
        $formViewCollection->vars['priceTypes'] = $priceTypes;
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier()
    {
        if (!static::$netPriceModeIdentifier) {
            static::$netPriceModeIdentifier = $this->getFactory()->getPriceFacade()->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier()
    {
        if (!static::$grossPriceModeIdentifier) {
            static::$grossPriceModeIdentifier = $this->getFactory()->getPriceFacade()->getGrossPriceModeIdentifier();
        }

        return static::$grossPriceModeIdentifier;
    }
}
