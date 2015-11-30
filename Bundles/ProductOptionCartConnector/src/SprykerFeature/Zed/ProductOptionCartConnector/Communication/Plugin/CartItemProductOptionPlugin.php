<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\ChangeTransfer;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade;

/**
 * @method ProductOptionCartConnectorFacade getFacade()
 */
class CartItemProductOptionPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        $this->getFacade()->expandProductOptions($change);

        return $change;
    }

}
