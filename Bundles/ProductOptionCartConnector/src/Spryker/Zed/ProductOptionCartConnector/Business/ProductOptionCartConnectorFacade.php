<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductOptionCartConnectorDependencyContainer getBusinessFactory()
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
        return $this->getBusinessFactory()->createProductOptionManager()->expandProductOptions($change);
    }

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandGroupKey(ChangeTransfer $change)
    {
        return $this->getBusinessFactory()->createGroupKeyExpander()->expand($change);
    }

}
