<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Business;

use Generated\Shared\Transfer\ChangeTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductOptionCartConnectorDependencyContainer getDependencyContainer()
 */
class ProductOptionCartConnectorFacade extends AbstractFacade
{

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandProductOptions(ChangeTransfer $change)
    {
        return $this->getDependencyContainer()->createProductOptionManager()->expandProductOptions($change);
    }

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandGroupKey(ChangeTransfer $change)
    {
        return $this->getDependencyContainer()->createGroupKeyExpander()->expand($change);
    }

}
