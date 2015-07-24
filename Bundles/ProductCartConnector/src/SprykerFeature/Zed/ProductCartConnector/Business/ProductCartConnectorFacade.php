<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCartConnector\Business;

use Generated\Shared\ProductCartConnector\ChangeInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductCartConnectorDependencyContainer getDependencyContainer()
 */
class ProductCartConnectorFacade extends AbstractFacade
{

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandItems(ChangeInterface $change)
    {
        return $this->getDependencyContainer()->createProductManager()->expandItems($change);
    }

}
