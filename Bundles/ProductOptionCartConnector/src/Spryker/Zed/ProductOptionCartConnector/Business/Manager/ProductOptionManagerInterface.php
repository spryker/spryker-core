<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Manager;

use Generated\Shared\Transfer\ChangeTransfer;

interface ProductOptionManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function expandProductOptions(ChangeTransfer $change);

}
