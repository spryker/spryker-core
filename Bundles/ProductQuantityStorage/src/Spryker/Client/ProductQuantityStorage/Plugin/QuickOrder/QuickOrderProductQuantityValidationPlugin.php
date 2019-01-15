<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidationPluginInterface;

/**
 * @method \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageClientInterface getClient()
 * @method \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageFactory getFactory()
 */
class QuickOrderProductQuantityValidationPlugin extends AbstractPlugin implements QuickOrderValidationPluginInterface
{
    /**
     * {@inheritdoc}
     * - Validate provided QuickOrderItemTransfer with quantity validation.
     * - Returns the unchanged provided QuickOrderItemTransfer when no validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function validateQuickOrderItemProduct(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer
    {
        return $this->getFactory()
            ->createQuantityQuickOrderTransferValidator()
            ->validateQuickOrderItem($quickOrderItemTransfer);
    }
}
