<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCartConnector\Communication\Plugin;

use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductCartConnector\Business\ProductCartConnectorFacade;

/**
 * @method ProductCartConnectorFacade getFacade()
 */
class ProductCartPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandItems(ChangeInterface $change)
    {
        $this->getFacade()->expandItems($change);

        return $change;
    }

}
