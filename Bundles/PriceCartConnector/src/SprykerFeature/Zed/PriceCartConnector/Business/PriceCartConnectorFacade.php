<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PriceCartConnector\Business;

use Generated\Shared\PriceCartConnector\ChangeInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method PriceCartConnectorDependencyContainer getDependencyContainer()
 */
class PriceCartConnectorFacade extends AbstractFacade
{

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function addGrossPriceToItems(ChangeInterface $change)
    {
        return $this->getDependencyContainer()->createPriceManager()->addGrossPriceToItems($change);
    }

}
