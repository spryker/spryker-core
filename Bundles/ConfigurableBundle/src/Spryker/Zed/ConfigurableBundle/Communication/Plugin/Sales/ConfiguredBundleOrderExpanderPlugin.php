<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Business\ConfigurableBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundle\Communication\ConfigurableBundleCommunicationFactory getFactory()
 */
class ConfiguredBundleOrderExpanderPlugin extends AbstractPlugin implements OrderExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands sales order by configured bundles.
     * - Expands ItemTransfer by configured bundle item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrate(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->hydrateConfiguredBundlesToOrder($orderTransfer);
    }
}
