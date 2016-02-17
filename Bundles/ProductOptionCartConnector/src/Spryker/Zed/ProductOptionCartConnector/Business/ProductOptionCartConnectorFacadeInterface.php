<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;

interface ProductOptionCartConnectorFacadeInterface
{

    /**
     * @param CartChangeTransfer $change
     *
     * @return CartChangeTransfer
     */
    public function expandProductOptions(CartChangeTransfer $change);

    /**
     * @param CartChangeTransfer $change
     *
     * @return CartChangeTransfer
     */
    public function expandGroupKey(CartChangeTransfer $change);

}
