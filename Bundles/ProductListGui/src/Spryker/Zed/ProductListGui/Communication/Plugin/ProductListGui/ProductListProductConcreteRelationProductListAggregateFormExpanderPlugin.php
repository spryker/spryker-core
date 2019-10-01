<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Plugin\ProductListGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListAggregateFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductListProductConcreteRelationProductListAggregateFormExpanderPlugin extends AbstractPlugin implements ProductListAggregateFormExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands ProductListAggregateFormType with ProductListProductConcreteRelationFormType subform and related helper fields.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        return $this->getFactory()
            ->createProductListAggregateFormExpander()
            ->expandWithProductListProductConcreteRelationForm($builder, $options);
    }
}
