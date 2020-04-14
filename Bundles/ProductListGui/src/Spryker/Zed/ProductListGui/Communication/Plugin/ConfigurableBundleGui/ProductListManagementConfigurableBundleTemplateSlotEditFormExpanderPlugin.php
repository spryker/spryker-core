<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui;

use Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductListManagementConfigurableBundleTemplateSlotEditFormExpanderPlugin extends AbstractPlugin implements ConfigurableBundleTemplateSlotEditFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ConfigurableBundleTemplateSlotEditForm with Product List assignment subforms.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void
    {
        $this->getFactory()
            ->createProductListAggregateFormExpander()
            ->expandWithProductListAssignmentForms($builder, $options);
    }
}
