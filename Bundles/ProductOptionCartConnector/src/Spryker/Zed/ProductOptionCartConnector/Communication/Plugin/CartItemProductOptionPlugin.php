<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade getFacade()
 * @method \Spryker\Zed\ProductOptionCartConnector\Communication\ProductOptionCartConnectorCommunicationFactory getFactory()
 */
class CartItemProductOptionPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        $this->getFacade()->expandProductOptions($change);

        return $change;
    }

}
