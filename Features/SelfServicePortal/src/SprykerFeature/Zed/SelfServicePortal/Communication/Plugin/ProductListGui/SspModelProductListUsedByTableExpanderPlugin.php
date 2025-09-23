<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductListGui;

use Generated\Shared\Transfer\ProductListUsedByTableTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListUsedByTableExpanderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 */
class SspModelProductListUsedByTableExpanderPlugin extends AbstractPlugin implements ProductListUsedByTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands table data by adding SSP models related to the product list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListUsedByTableTransfer $productListUsedByTableTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableTransfer
     */
    public function expand(ProductListUsedByTableTransfer $productListUsedByTableTransfer): ProductListUsedByTableTransfer
    {
        return $this->getFactory()
            ->createSspModelProductListUsedByTableExpander()
            ->expandTableData($productListUsedByTableTransfer);
    }
}
