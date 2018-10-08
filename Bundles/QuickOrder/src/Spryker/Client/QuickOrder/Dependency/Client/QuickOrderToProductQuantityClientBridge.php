<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Dependency\Client;

use Generated\Shared\Transfer\ProductQuantityTransfer;
use Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer;

class QuickOrderToProductQuantityClientBridge implements QuickOrderToProductQuantityClientInterface
{
    /**
     * @var \Spryker\Client\ProductQuantity\ProductQuantityClientInterface
     */
    protected $productQuantityClient;

    /**
     * @param \Spryker\Client\ProductQuantity\ProductQuantityClientInterface $productQuantityClient
     */
    public function __construct($productQuantityClient)
    {
        $this->productQuantityClient = $productQuantityClient;
    }

    /**
     * @param int $quantity
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    public function validateProductQuantityRestrictions(int $quantity, ProductQuantityTransfer $productQuantityTransfer): ProductQuantityValidationResponseTransfer
    {
        return $this->productQuantityClient->validateProductQuantityRestrictions($quantity, $productQuantityTransfer);
    }
}
