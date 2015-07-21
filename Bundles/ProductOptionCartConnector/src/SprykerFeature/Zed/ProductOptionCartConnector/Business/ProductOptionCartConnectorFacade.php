<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Business;

use Generated\Shared\Cart\ChangeInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductOptionCartConnectorDependencyContainer getDependencyContainer()
 */
class ProductOptionCartConnectorFacade extends AbstractFacade
{

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandProductOptions(ChangeInterface $change)
    {
        return $this->getDependencyContainer()->createProductOptionManager()->expandProductOptions($change);
    }
}
