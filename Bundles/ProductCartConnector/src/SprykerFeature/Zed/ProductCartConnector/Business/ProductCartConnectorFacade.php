<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCartConnector\Business;

use Generated\Shared\Transfer\ChangeTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductCartConnectorDependencyContainer getDependencyContainer()
 */
class ProductCartConnectorFacade extends AbstractFacade
{

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        return $this->getDependencyContainer()->createProductManager()->expandItems($change);
    }

}
