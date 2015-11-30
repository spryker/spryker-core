<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\ChangeTransfer;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductCartConnector\Business\ProductCartConnectorFacade;

/**
 * @method ProductCartConnectorFacade getFacade()
 */
class ProductCartPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        $this->getFacade()->expandItems($change);

        return $change;
    }

}
