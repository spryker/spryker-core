<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Price;

use Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Spryker\Shared\ProductManagement\ProductManagementConstants;

class ProductMoneyCollectionType extends MoneyCollectionType
{
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
        $priceTypes = [
            ProductManagementConstants::PRICE_MODE_NET => [],
            ProductManagementConstants::PRICE_MODE_GROSS => []
        ];

        foreach ($view as $item) {
            $moneyValue = $item['moneyValue'];

            /* @var $priceTypeTransfer \Generated\Shared\Transfer\PriceTypeTransfer  */
            $priceTypeTransfer = $item->vars['price_type'];

            $priceType = $priceTypeTransfer->getName();
            $priceModeConfiguration = $priceTypeTransfer->getPriceModeConfiguration();

            $storeName = $moneyValue->vars['store_name'];
            $currencySymbol = $moneyValue->vars['currency_symbol'];

            if ($priceModeConfiguration == ProductManagementConstants::PRICE_MODE_BOTH) {
                $priceTypes[ProductManagementConstants::PRICE_MODE_NET][$priceType] = $priceTypeTransfer;
                $priceTypes[ProductManagementConstants::PRICE_MODE_GROSS][$priceType] = $priceTypeTransfer;
                $priceTable[$storeName][$currencySymbol][ProductManagementConstants::PRICE_MODE_NET][$priceType] = $item;
                $priceTable[$storeName][$currencySymbol][ProductManagementConstants::PRICE_MODE_GROSS][$priceType] = $item;

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
}
