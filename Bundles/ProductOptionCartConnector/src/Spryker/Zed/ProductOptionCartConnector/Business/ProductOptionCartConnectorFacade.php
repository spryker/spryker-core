<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductOptionCartConnectorBusinessFactory getFactory()
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
        return $this->getFactory()->createProductOptionManager()->expandProductOptions($change);
    }

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandGroupKey(ChangeTransfer $change)
    {
        return $this->getFactory()->createGroupKeyExpander()->expand($change);
    }

}
