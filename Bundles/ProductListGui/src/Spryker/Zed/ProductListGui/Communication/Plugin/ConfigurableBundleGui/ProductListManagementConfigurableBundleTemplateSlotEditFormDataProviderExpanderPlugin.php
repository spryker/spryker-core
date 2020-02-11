<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer;
use Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormDataProviderExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductListManagementConfigurableBundleTemplateSlotEditFormDataProviderExpanderPlugin extends AbstractPlugin implements ConfigurableBundleTemplateSlotEditFormDataProviderExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands options for ConfigurableBundleTemplateSlotEditForm with Product List management data.
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function expandOptions(array $options): array
    {
        return $this->getFactory()
            ->createProductListAggregateFormDataProviderExpander()
            ->expandOptions($options);
    }

    /**
     * {@inheritDoc}
     * - Expands form data for ConfigurableBundleTemplateSlotEditForm with Product List management data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer
     */
    public function expandData(ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer): ConfigurableBundleTemplateSlotEditFormTransfer
    {
        $productListAggregateFormTransfer = $this->getFactory()
            ->createProductListAggregateFormDataProviderExpander()
            ->expandProductListAggregateFormData($configurableBundleTemplateSlotEditFormTransfer->getProductListAggregateForm());

        return $configurableBundleTemplateSlotEditFormTransfer->setProductListAggregateForm($productListAggregateFormTransfer);
    }
}
