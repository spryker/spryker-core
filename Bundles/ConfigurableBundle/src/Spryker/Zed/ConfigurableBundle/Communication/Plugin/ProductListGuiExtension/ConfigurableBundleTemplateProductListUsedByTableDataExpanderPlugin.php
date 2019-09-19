<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Communication\Plugin\ProductListGuiExtension;

use Generated\Shared\Transfer\ProductListUsedByTableDataTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListUsedByTableDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundle\Business\ConfigurableBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundle\Communication\ConfigurableBundleCommunicationFactory getFactory()
 */
class ConfigurableBundleTemplateProductListUsedByTableDataExpanderPlugin extends AbstractPlugin implements ProductListUsedByTableDataExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands table data with Configurable Bundle Templates and Slots which use Product List.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    public function expand(ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer): ProductListUsedByTableDataTransfer
    {
        return $this->getFacade()->expandProductListUsedByTableData($productListUsedByTableDataTransfer);
    }
}
