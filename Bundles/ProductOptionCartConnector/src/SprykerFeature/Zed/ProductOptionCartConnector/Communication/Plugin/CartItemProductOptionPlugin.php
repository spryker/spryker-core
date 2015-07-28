<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Communication\Plugin;

use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade;

/**
 * @method ProductOptionCartConnectorFacade getFacade()
 */
class CartItemProductOptionPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandItems(ChangeInterface $change)
    {
        $this->getFacade()->expandProductOptions($change);

        return $change;
    }

}
