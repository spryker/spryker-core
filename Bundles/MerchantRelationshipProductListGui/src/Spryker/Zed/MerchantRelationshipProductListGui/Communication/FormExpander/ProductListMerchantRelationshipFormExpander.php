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

class ProductListMerchantRelationshipFormExpander implements ProductListMerchantRelationshipFormExpanderInterface
{
    public const OPTION_PRODUCT_LIST_CHOICES = 'choices';
    public const OPTION_DATA = 'data';

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
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $productListOptions = $this->productListMerchantRelationshipFormDataProvider->getOptions($event->getData());

            $event->getForm()
                ->add(MerchantRelationshipTransfer::PRODUCT_LIST_IDS, Select2ComboBoxType::class, [
                    'label' => 'Assigned Product Lists',
                    'multiple' => true,
                    'required' => false,
                    'choices' => $productListOptions[static::OPTION_PRODUCT_LIST_CHOICES],
                    'data' => $productListOptions[static::OPTION_DATA],
                ]);
        });

        return $builder;
    }
}
