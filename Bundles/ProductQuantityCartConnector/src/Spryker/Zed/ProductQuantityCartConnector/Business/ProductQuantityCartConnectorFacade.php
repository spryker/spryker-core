<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductQuantityCartConnector\Business\ProductQuantityCartConnectorBusinessFactory getFactory()
 */
class ProductQuantityCartConnectorFacade extends AbstractFacade implements ProductQuantityCartConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateProductQuantityRestrictions(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createProductQuantityRestrictionValidator()
            ->validateItems($cartChangeTransfer);
    }
}
