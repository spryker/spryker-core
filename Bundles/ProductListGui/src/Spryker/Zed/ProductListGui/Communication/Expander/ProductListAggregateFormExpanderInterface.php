<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Expander;

use Symfony\Component\Form\FormBuilderInterface;

interface ProductListAggregateFormExpanderInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandWithProductListCategoryRelationForm(FormBuilderInterface $builder, array $options): FormBuilderInterface;

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandWithProductListProductConcreteRelationForm(FormBuilderInterface $builder, array $options): FormBuilderInterface;
}
