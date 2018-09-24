<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantity;

use Generated\Shared\Transfer\ProductQuantityTransfer;
use Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductQuantity\ProductQuantityFactory getFactory()
 */
class ProductQuantityClient extends AbstractClient implements ProductQuantityClientInterface
{
    /**
     * @api
     *
     * @param int $quantity
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    public function validateProductQuantityRestrictions(int $quantity, ProductQuantityTransfer $productQuantityTransfer): ProductQuantityValidationResponseTransfer
    {
        return $this->getFactory()
            ->createProductQuantityRestrictionsValidator()
            ->validateProductQuantityRestrictions($quantity, $productQuantityTransfer);
    }
}
