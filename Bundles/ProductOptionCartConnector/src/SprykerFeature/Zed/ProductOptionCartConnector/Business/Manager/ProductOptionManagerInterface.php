<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Manager;

use Generated\Shared\Transfer\ChangeTransfer;

interface ProductOptionManagerInterface
{

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandProductOptions(ChangeTransfer $change);

}
