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
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $priceTable = [];

        $grossPriceModeIdentifier = $this->getGrossPriceModeIdentifier();
        $netPriceModeIdentifier = $this->getNetPriceModeIdentifier();

        $priceTypes = [
            $grossPriceModeIdentifier => [],
            $netPriceModeIdentifier => [],
        ];

        foreach ($view as $item) {
            $moneyValue = $item['moneyValue'];

            /** @var \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer */
            $priceTypeTransfer = $item->vars['price_type'];

            $priceType = $priceTypeTransfer->getName();
            $priceModeConfiguration = $priceTypeTransfer->getPriceModeConfiguration();

            $storeName = $moneyValue->vars['store_name'];
            $currencySymbol = $moneyValue->vars['currency_symbol'];

            if ($priceModeConfiguration == ProductManagementConstants::PRICE_MODE_BOTH) {
                $priceTypes[$netPriceModeIdentifier][$priceType] = $priceTypeTransfer;
                $priceTypes[$grossPriceModeIdentifier][$priceType] = $priceTypeTransfer;

                $priceTable[$storeName][$currencySymbol][$netPriceModeIdentifier][$priceType] = $item;
                $priceTable[$storeName][$currencySymbol][$grossPriceModeIdentifier][$priceType] = $item;
            } else {
                if (!isset($priceTypes[$priceModeConfiguration][$priceType])) {
                    $priceTypes[$priceModeConfiguration][$priceType] = $priceTypeTransfer;
                }

                $priceTable[$storeName][$currencySymbol][$priceModeConfiguration][$priceType] = $item;
            }
        }

        $view->vars['priceTable'] = $priceTable;
        $view->vars['priceTypes'] = $priceTypes;
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
