<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Form\Expander;

use Spryker\Zed\ProductBundle\Communication\Form\DataProvider\ProductBundleReturnCreateFormDataProvider;
use Spryker\Zed\ProductBundle\Communication\Form\ReturnCreateBundleItemsSubForm;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductBundleReturnCreateFormExpander implements ProductBundleReturnCreateFormExpanderInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->add(
            ProductBundleReturnCreateFormDataProvider::FIELD_RETURN_BUNDLE_ITEMS,
            CollectionType::class,
            [
                'entry_type' => ReturnCreateBundleItemsSubForm::class,
                'entry_options' => [
                    ProductBundleReturnCreateFormDataProvider::OPTION_RETURN_REASONS => $options[ProductBundleReturnCreateFormDataProvider::OPTION_RETURN_REASONS],
                ],
                'label' => false,
            ]
        );

        return $builder;
    }
}
