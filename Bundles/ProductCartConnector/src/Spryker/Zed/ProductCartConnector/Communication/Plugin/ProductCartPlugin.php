<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacade;
use Spryker\Zed\ProductCartConnector\Communication\ProductCartConnectorCommunicationFactory;

/**
 * @method ProductCartConnectorFacade getFacade()
 * @method ProductCartConnectorCommunicationFactory getFactory()
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
