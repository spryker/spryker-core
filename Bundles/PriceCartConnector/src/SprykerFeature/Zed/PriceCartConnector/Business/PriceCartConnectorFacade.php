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
     * @param null $grossPriceType
     *
     * @return ChangeInterface
     */
    public function addGrossPriceToItems(ChangeInterface $change, $grossPriceType = null)
    {
        return $this->getDependencyContainer()->createPriceManager($grossPriceType)->addGrossPriceToItems($change);
    }

}
