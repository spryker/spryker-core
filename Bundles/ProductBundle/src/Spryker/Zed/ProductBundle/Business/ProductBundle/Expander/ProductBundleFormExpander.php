<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Expander;

use Symfony\Component\Form\FormBuilderInterface;

class ProductBundleFormExpander implements ProductBundleFormExpanderInterface
{
    public const FIELD_RETURN_BUNDLE_ITEMS = 'returnBundleItems';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandFormWithProductBundles(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        return $builder;
    }
}
