<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\FormExpander;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider\ProductListMerchantRelationshipFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductListMerchantRelationshipEditFormExpander implements ProductListMerchantRelationshipEditFormExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider\ProductListMerchantRelationshipFormDataProvider
     */
    protected $productListMerchantRelationshipFormDataProvider;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider\ProductListMerchantRelationshipFormDataProvider $productListMerchantRelationshipFormDataProvider
     */
    public function __construct(
        ProductListMerchantRelationshipFormDataProvider $productListMerchantRelationshipFormDataProvider
    ) {
        $this->productListMerchantRelationshipFormDataProvider = $productListMerchantRelationshipFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $event->getForm()
                ->add(MerchantRelationshipTransfer::PRODUCT_LIST_IDS, Select2ComboBoxType::class, array_merge([
                    'label' => 'Assigned Product Lists',
                    'multiple' => true,
                    'required' => false,
                ], $this->productListMerchantRelationshipFormDataProvider->getOptions($event->getData())));
        });
    }
}
