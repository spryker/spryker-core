<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Plugin\ProductListGuiExtension;

use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTopButtonsExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class ConfigurableBundleTemplateListProductListTopButtonsExpanderPlugin extends AbstractPlugin implements ProductListTopButtonsExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands buttons list with button leads to a Cofigurable Bundle Template list page.
     *
     * @api
     *
     * @see \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::indexAction()
     *
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    public function expand(ButtonCollectionTransfer $buttonCollectionTransfer): ButtonCollectionTransfer
    {
        return $this->getFactory()
            ->createProductListButtonsExpander()
            ->expandButtonCollection($buttonCollectionTransfer);
    }
}
